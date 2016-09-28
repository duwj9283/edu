<?php
namespace Cloud\API\Controllers;
use Cloud\Models\Subject;
use Cloud\Models\Usercapacity;
use Cloud\Models\Usercapacityapply;
use Cloud\Models\Userdynamic;
use Cloud\Models\Userdynamiccomment;
use Cloud\Models\Userfile;
use Cloud\Models\Userfilepush;
use Cloud\Models\Userfileshare;
use Cloud\Models\Userfollow;
use Cloud\Models\User;
use Cloud\Models\Userinfo;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\StringLength;
/**
 * @brief 用户数据接口
 */
class UserController extends ControllerBase
{

    /**
     * @brief 更新个人资料
     * @param [nick_name|sex|headpic|city|qq|labels]
     */
    public function updateInfoAction()
    {
        do{
            if(!$this->checkUserLogin())
            {
                break;
            }
            $uid = $this->uid;
            $userInfo = Userinfo::findFirst("uid=$uid");
            foreach($this->params as $key => $value){
                if( isset($userInfo->$key)){
                    $userInfo->$key = $this->params[$key];
                }
            }
            //  更新个人资料
            if( !$userInfo->update()){
                $this->responseData = array("code"=>\Code::ERROR_DB_WRITE,"msg"=>join(';', $userInfo->getMessages()),"line"=>__LINE__,"data"=>array());
                break;
            }
        }while(false);
        $this->output();
    }

    /**
     * @brief 修改密码
     * @param oldpwd
     * @param newpwd
     */
    public function modifyPwdAction()
    {
        do{
            $this->validation->add("oldpwd", new StringLength(array( "max" => 18,
                "min" => 6,
                "messageMaximum" => "长度超出:oldpwd",
                "messageMinimum" => "长度不足:oldpwd"
            )));
            $this->validation->add("newpwd", new StringLength(array( "max" => 6,
                "min" => 6,
                "messageMaximum" => "长度超出:newpwd",
                "messageMinimum" => "长度不足:newpwd"
            )));
            $messages = $this->validation->validate($this->params);
            if (count($messages)) {
                foreach ($messages as $message) {
                    array_push($this->errors, strval($message));
                }
                $this->responseData = array("code"=>\Code::ERROR_PARAMS,"msg"=>join(';', $this->errors),"line"=>__LINE__,"data"=>array());
                break;
            }
            if(!$this->checkUserLogin())
            {
                break;
            }
            $uid = $this->uid;
            $oldpwd = $this->params['oldpwd'];
            $newpwd = $this->params['newpwd'];

            $user = User::findFirst(array('uid='.$uid));
            //  判断旧密码是否正确
            if (!$this->security->checkHash($oldpwd, $user->password))
            {
                $this->responseData = array("code"=>\Code::ERROR,"msg"=>"原密码不正确","line"=>__LINE__,"data"=>array());
                break;
            }
            //  设置新密码
            $user->password = $this->security->Hash($newpwd);;
            if( !$user->save() ){
                $this->responseData = array("code"=>\Code::ERROR_DB,"msg"=>"重置密码失败","line"=>__LINE__,"data"=>array());
                break;
            }
        }while(false);
        $this->output();
    }

	/**
	 * @brief 用户间的关注
	 * @param tuid 目标用户ID
	 */
	public function followAction(){

		do{
			$this->validation->add('tuid', new PresenceOf(array('message'=>'参数缺失:tuid')));
			$messages = $this->validation->validate($this->params);
			if (count($messages)) {
				foreach ($messages as $message) {
					array_push($this->errors, strval($message));
				}
				$this->responseData = array("code"=>\Code::ERROR_PARAMS,"msg"=>join(';', $this->errors),"line"=>__LINE__,"data"=>array());
				break;
			}
            if(!$this->checkUserLogin())
            {
                break;
            }
			$uid = $this->uid;
			$tuid = intval($this->params['tuid']);
            if($uid==$tuid)
            {
                $this->responseData = array("code"=>\Code::ERROR_DB,"msg"=>"您不能关注自己的空间","line"=>__LINE__,"data"=>array());
                break;
            }
			$userFollow = Userfollow::findFirst(array(
				"conditions"=>"uid=$uid and tuid=$tuid",
				"limit"=>1
			));

			if($userFollow){
				$this->responseData = array("code"=>\Code::ERROR_DB,"msg"=>"你已经关注过他了","line"=>__LINE__,"data"=>array());
                break;
			}
            $this->db->begin();
            //添加关注
			$userFollow = new Userfollow();
			$userFollow->uid = $uid;
			$userFollow->tuid = $tuid;
			if(!$userFollow->create()){
                $this->db->rollback();
				$this->responseData = array("code"=>\Code::ERROR_DB,"msg"=>"关注失败","line"=>__LINE__,"data"=>array());
                break;
			}
            $userInfo = Userinfo::findFirst(array("uid=$tuid"));
            $userInfo->follow_count = $userInfo->follow_count+1;
            if(!$userInfo->update())
            {
                $this->db->rollback();
                $this->responseData = array("code"=>\Code::ERROR_DB,"msg"=>"关注失败2","line"=>__LINE__,"data"=>array());
                break;
            }
            $this->db->commit();
		}while(false);
		$this->output();
	}

	/**
	 * @brief 取消关注
	 * @param tuid 目标用户ID
	 */
	public function unFollowAction(){

		do{
			$this->validation->add('tuid', new PresenceOf(array('message'=>'参数缺失:tuid')));
			$messages = $this->validation->validate($this->params);
			if (count($messages)) {
				foreach ($messages as $message) {
					array_push($this->errors, strval($message));
				}
				$this->responseData = array("code"=>\Code::ERROR_PARAMS,"msg"=>join(';', $this->errors),"line"=>__LINE__,"data"=>array());
				break;
			}

			$uid = $this->uid;
			$tuid = intval($this->request->getPost('tuid'));
			$userFollow = Userfollow::findFirst(array(
				"conditions"=>"uid=$uid and tuid=$tuid",
				"limit"=>1
			));

			if(!$userFollow){
				$this->responseData = array("code"=>\Code::ERROR_DB,"msg"=>"你还没有关注他(她)","line"=>__LINE__,"data"=>array());
                break;
			}
            $this->db->begin();
			$cancel = $this->db->query("delete from edu_user_follows where uid=$uid and tuid=$tuid limit 1");
			if(!$cancel)
            {
                $this->db->rollback();
				$this->responseData = array("code"=>\Code::ERROR_DB,"msg"=>"取消关注失败","line"=>__LINE__,"data"=>array());
                break;
			}
            $userInfo = Userinfo::findFirst(array("uid=$tuid"));
            $userInfo->follow_count = $userInfo->follow_count-1;
            if(!$userInfo->update())
            {
                $this->db->rollback();
                $this->responseData = array("code"=>\Code::ERROR_DB,"msg"=>"取消关注失败2","line"=>__LINE__,"data"=>array());
                break;
            }
            $this->db->commit();
		}while(false);
		$this->output();
	}

    /**
     * @brief 查看剩余容量
     * @param 无
     */
    public function getUserCapacityAction()
    {
        do{
            if(!$this->checkUserLogin())
            {
                break;
            }
            $uid = $this->uid;

            $userCapacity = Usercapacity::findFirst(array("uid=$uid"));
            if(!$userCapacity)
            {
                $userCapacity = new Usercapacity();
                $userCapacity->uid = $uid;
                $userCapacity->capacity_all = 1024*1024*1024*10;
                $userCapacity->capacity_used = 0;
                if(!$userCapacity->save())
                {
                    $this->responseData = array("code"=>\Code::ERROR_DB,"msg"=>"获取失败","line"=>__LINE__,"data"=>array());
                    break;
                }
            }else
            {
                //计算用户已使用容量
                $usedCapacityCounter = Userfile::sum(array("uid=$uid and percent=100 and file_status=".\FileStatus::NORMAL." and file_type>".\FileType::FOLDER, "column"=>"file_size"));
                $userCapacity->capacity_used = $usedCapacityCounter;
                if(!$userCapacity->update())
                {
                    $this->responseData = array("code"=>\Code::ERROR_DB,"msg"=>"获取失败","line"=>__LINE__,"data"=>array());
                    break;
                }
            }
            $userCapacity = $userCapacity->toArray();
            $userCapacity['capacity_all'] = number_format($userCapacity['capacity_all']/1024/1024/1024,2);
            $userCapacity['capacity_used'] = number_format($userCapacity['capacity_used']/1024/1024/1024,2);
            $userCapacity['percent'] = 100*number_format($userCapacity['capacity_used']/$userCapacity['capacity_all'],2);
            $userCapacity['apply_status'] = 1;
            $userCapacityApply = Usercapacityapply::findFirst(array("uid=$uid and status=0","order"=>"id desc"));
            if($userCapacityApply)
            {
                $userCapacity['apply_status'] = 0;
            }
            $this->responseData['data']['capacity'] = $userCapacity;
        }while(false);
        $this->output();
    }

    /**
     * @brief 申请空间容量
     * @param reason
     */
    public function applyUserCapacityAction()
    {
//        $this->params = array("reason"=>"aaaaaaa");
        do{
            $this->validation->add('reason', new PresenceOf(array('message'=>'参数缺失:reason')));
            $messages = $this->validation->validate($this->params);
            if (count($messages)) {
                foreach ($messages as $message) {
                    array_push($this->errors, strval($message));
                }
                $this->responseData = array("code"=>\Code::ERROR_PARAMS,"msg"=>join(';', $this->errors),"line"=>__LINE__,"data"=>array());
                break;
            }
            if(!$this->checkUserLogin())
            {
                break;
            }
            $uid = $this->uid;
            $reason = $this->params['reason'];
            $userCapacityApply = Usercapacityapply::findFirst(array("uid=$uid","order"=>"id desc"));
            if(!$userCapacityApply)
            {
                $userCapacity = new Usercapacityapply();
                $userCapacity->uid = $uid;
                $userCapacity->reason = $reason;
                if(!$userCapacity->save())
                {
                    $this->responseData = array("code"=>\Code::ERROR_DB,"msg"=>"申请失败","line"=>__LINE__,"data"=>array());
                    break;
                }
            }
            else
            {
                if($userCapacityApply->status==0)
                {
                    $this->responseData = array("code"=>\Code::ERROR_DB,"msg"=>"您已提交申请，请耐心等待审核","line"=>__LINE__,"data"=>array());
                    break;
                }
                else
                {
                    $userCapacity = new Usercapacityapply();
                    $userCapacity->uid = $uid;
                    $userCapacity->reason = $reason;
                    if(!$userCapacity->save())
                    {
                        $this->responseData = array("code"=>\Code::ERROR_DB,"msg"=>"申请失败","line"=>__LINE__,"data"=>array());
                        break;
                    }
                }
            }
        }while(false);
        $this->output();
    }

    /**
     * @brief 个人空间动态列表
     * @param (选填) uid
     * @param（选填）page 当前页 默认是：1
     */
    public function getDynamicListAction()
    {
        do{
            if(!$this->checkUserLogin())
            {
                break;
            }
            $uid = isset($this->params['uid'])?(int)$this->params['uid']:$this->uid;
            $page = isset($this->params['page'])&&(int)$this->params['page']>0?(int)$this->params['page']:1;
            $limit = 12;
            $offset = ($page - 1) * $limit;
            $userDynamics = Userdynamic::find(array("uid=$uid and status=1","limit"=>$limit,"offset"=>$offset,"order"=>"addtime desc"));
            $dynamics = array();
            foreach($userDynamics as $k=>$userDynamic)
            {
                $dynamics[$k]['dynamic'] = $userDynamic->toArray();
                $dynamicComments = $userDynamic->getDynamicComment();

                foreach($dynamicComments as $dynamicComment){
                    $user_file_comment_info = $dynamicComment->toArray();
                    $user_file_comment_info['date'] = $this->dateConv($user_file_comment_info['create_time']);
                    if( $dynamicComment->ref_uid == 0 ){
                        $user_file_comment_info['refUserName'] = "";
                        $user_file_comment_info['refHeadpic'] = array();
                    }
                    else
                    {
                        $refUserInfo = $dynamicComment->getRefUserInfo();
                        $user_file_comment_info['refUserName'] = $refUserInfo->nick_name;
                        $user_file_comment_info['refHeadpic'] = $refUserInfo->headpic;
                    }
                    $userInfo = $dynamicComment->getUserInfo();
                    $user_file_comment_info['userName'] = $userInfo->nick_name;
                    $user_file_comment_info['headpic'] = $userInfo->headpic;
                    if($dynamicComment->ref_id == 0 ){
                        $dynamics[$k]['comment'][$dynamicComment->id][] = $user_file_comment_info;
                    }
                    else
                    {
                        $dynamics[$k]['comment'][$dynamicComment->ref_id][] = $user_file_comment_info;
                    }
                }
            }
            $this->responseData['data']['dynamics'] = $dynamics;
            $counter = Userdynamic::count(array("uid=$uid and status=1"));
            $this->responseData['data']['total'] = $counter;
        }while(false);
        $this->output();
    }

    /**
     * @brief 评价空间动态
     * @param dynamic_id 动态ID
     * @param ref_uid 回复某个人的ID,非回复则为0
     * @param ref_id 回复的评论ID(请注意与lesson_id的区别,每个课程有个ID,每个评论也有个ID,这个是评论的ID),非回复则为0
     * @param content 评论的内容
     */
    public function commentDynamicAction()
    {
//        $this->params = array('file_id'=>152,'ref_uid'=>0,'ref_id'=>0,'content'=>'这个文件好给力\(^o^)/~');
        do {
            $this->validation->add('dynamic_id', new PresenceOf(array('message'=>'参数缺失:dynamic_id')));
            $this->validation->add('ref_uid', new PresenceOf(array('message'=>'参数缺失:ref_uid')));
            $this->validation->add('ref_id', new PresenceOf(array('message'=>'参数缺失:ref_id')));
            $this->validation->add('content', new PresenceOf(array('message'=>'参数缺失:content')));
            $messages = $this->validation->validate($this->params);
            if (count($messages)) {
                foreach ($messages as $message) {
                    array_push($this->errors, strval($message));
                }
                $this->responseData = array("code"=>\Code::ERROR_PARAMS,"msg"=>join(';', $this->errors),"line"=>__LINE__,"data"=>array());
                break;
            }
            if(!$this->checkUserLogin())
            {
                break;
            }
            $uid = $this->uid;
            $dynamic_id = (int)$this->params['dynamic_id'];
            $ref_uid = (int)$this->params['ref_uid'];
            $ref_id = (int)$this->params['ref_id'];
            $content = $this->params['content'];
            $dynamic = Userdynamic::findFirst(array("id=$dynamic_id and status=1"));
            if(!$dynamic)
            {
                $this->responseData = array("code"=>\Code::ERROR_PARAMS,"msg"=>'动态不存在',"line"=>__LINE__,"data"=>array());
                break;
            }
            $userDynamicComment = new Userdynamiccomment();
            $userDynamicComment->uid = $uid;
            $userDynamicComment->user_dynamic_id = $dynamic_id;
            $userDynamicComment->content = $content;
            $userDynamicComment->ref_id = $ref_id;
            $userDynamicComment->ref_uid = $ref_uid;
            $userDynamicComment->create_time = date('Y-m-d H:i:s');
            if(!$userDynamicComment->create())
            {
                $this->responseData = array("code"=>\Code::ERROR_DB,"msg"=>'评价失败',"line"=>__LINE__,"data"=>array());
                break;
            }
            $this->responseData['data']['content'] = $userDynamicComment->toArray();
            $this->responseData['data']['userInfo'] = $userDynamicComment->getUserinfo();
            $this->responseData['data']['refUserInfo'] = $userDynamicComment->getRefUserInfo();
        }while(false);
        $this->output();
    }

    /**
     * @brief 空间列表
     * @param (选填) keywords
     * @param（选填）page 当前页 默认是：1
     * @param（选填）father_subject 学科ID
     */
    public function getZoneListAction()
    {
        do{
            $keywords = isset($this->params['keywords'])?$this->params['keywords']:'';
            $page = isset($this->params['page'])&&(int)$this->params['page']>0?(int)$this->params['page']:1;
            $father_subject = isset($this->params['father_subject'])&&(int)$this->params['father_subject']>0?(int)$this->params['father_subject']:0;
            $limit = 12;
            $offset = ($page - 1) * $limit;
            $condition = "";
            if(!empty($keywords))
            {
                $condition .= " and nick_name like '%$keywords%' ";
            }
            if($father_subject)
            {
                $condition .= " and father_subject=$father_subject";
            }
            $userInfos = UserInfo::find(array("role_id=2 $condition","limit"=>$limit,"offset"=>$offset));
            $userInfoArr = array();
            $subjectIds = array();
            foreach($userInfos as $k=>$userInfo)
            {
                if(!in_array($userInfo->subject,$subjectIds))
                {
                    array_push($subjectIds,$userInfo->subject);
                }
                $userInfoArr[$k]['userInfo'] = $userInfo->toArray();
                //计算关注人数
                $followCounter = Userfollow::count(array("tuid=".$userInfo->uid));
                $userInfoArr[$k]['userInfo']['followCount'] = $followCounter;
            }
            if(!empty($subjectIds))
            {
                $subjects = Subject::find(array('id in ('.join(',',$subjectIds).')'));
                $subjectArr = array();
                foreach($subjects as $subject)
                {
                    $subjectArr[$subject->id] = $subject->subject_name;
                }
                foreach($userInfos as $k=>$userInfo)
                {
                    $userInfoArr[$k]['subject_name'] = $subjectArr[$userInfo->subject];
                }
            }
            $this->responseData['data']['userInfoList'] = array_values($userInfoArr);
            $counter = UserInfo::count(array("role_id=2 $condition"));
            $this->responseData['data']['total'] = $counter;
        }while(false);
        $this->output();
    }

    /**
     * @brief 密码重置
     * @param uid
     * @param password
     * @param auth_token 加密字符串  md5(uid+key+timestamp) key:Pwd$&*WIND758U
     * @param timestamp 时间戳 1450000000
     */
    public function resetPasswordAction()
    {
        do {
            $this->validation->add('uid', new PresenceOf(array('message'=>'参数缺失:uid')));
            $this->validation->add('password', new PresenceOf(array('message'=>'参数缺失:password')));
            $this->validation->add('auth_token', new PresenceOf(array('message'=>'参数缺失:auth_token')));
            $this->validation->add('timestamp', new PresenceOf(array('message'=>'参数缺失:timestamp')));
            $messages = $this->validation->validate($this->request->get());
            if (count($messages)) {
                foreach ($messages as $message) {
                    array_push($this->errors, strval($message));
                }
                $this->responseData = array("code"=>\Code::ERROR_PARAMS,"msg"=>join(';', $this->errors),"line"=>__LINE__,"data"=>array());
                break;
            }
            $uid = (int)$this->request->get('uid');
            $password = $this->request->get('password');
            $auth_token = $this->request->get('auth_token');
            $timestamp = $this->request->get('timestamp');
            $token = md5($uid.'Pwd$&*WIND758U'.$timestamp);
            if($token!=$auth_token)
            {
                $this->responseData = array("code"=>\Code::ERROR,"msg"=>"无效的重置","line"=>__LINE__,"data"=>array());
                break;
            }
            $user = User::findFirst(array( "uid = $uid" ));
            if(!$user)
            {
                $this->responseData = array("code"=>\Code::ERROR,"msg"=>"用户不存在","line"=>__LINE__,"data"=>array());
                break;
            }
            $user->is_forget = 0;
            $user->user_token = $this->security->getTokenKey();
            $user->password = $this->security->Hash($password);
            if(!$user->update())
            {
                $this->responseData = array("code"=>\Code::ERROR,"msg"=>"密码重置失败","line"=>__LINE__,"data"=>array());
                break;
            }
        }while(false);
        $this->output();
    }

    /**
     * @brief 我的发布
     * @param page 默认：1
     */
    public function getMyPushAction()
    {
        do{
            if(!$this->checkUserLogin())
            {
                $this->response->redirect("login/login");
                break;
            }
            $uid = $this->uid;
            $page = isset($this->params['page'])&&(int)$this->params['page']>0?$this->params['page']:1;
            $counter = 12;  //默认单次返回的数据数量
            $start = ($page-1)*$counter;
            $myPushList = Userfilepush::find(array("uid=$uid and status=1","order"=>"addtime desc",'limit'=>$counter,'offset'=>$start));
            $this->responseData['data']['myPushList'] = $myPushList->toArray();
            $counter = Userfilepush::count(array("uid=$uid and status=1"));
            $this->responseData['data']['total'] = $counter;
        }while(false);
        $this->output();
    }

    /**
     * @brief 我的分享
     * @param page 默认：1
     */
    public function getMyShareAction()
    {
        do{
            if(!$this->checkUserLogin())
            {
                $this->response->redirect("login/login");
                break;
            }
            $uid = $this->uid;
            $page = isset($this->params['page'])&&(int)$this->params['page']>0?$this->params['page']:1;
            $counter = 12;  //默认单次返回的数据数量
            $start = ($page-1)*$counter;
            $myShareList = Userfileshare::find(array("uid=$uid","order"=>"addtime desc",'limit'=>$counter,'offset'=>$start));
            $fileIds = array();
            $myShareArr = array();
            foreach($myShareList as $k=>$myShare)
            {
                if(!in_array($myShare->user_file_id,$fileIds))
                {
                    array_push($fileIds,$myShare->user_file_id);
                }
            }
            if(!empty($fileIds))
            {
                $userFiles = Userfile::find(array("id in(".join(',',$fileIds).")"));
                $userFileArr = array();
                foreach($userFiles as $userFile)
                {
                    $userFileArr[$userFile->id]['file_name'] = $userFile->file_name;
                }
                foreach($myShareList as $k=>$myShare)
                {
                    $myShareArr[$k] = $myShare->toArray();
                    $myShareArr[$k]['file_name'] = $userFileArr[$myShare->user_file_id]['file_name'];
                }
            }
            $this->responseData['data']['myShareList'] =$myShareArr;
            $counter = Userfileshare::count(array("uid=$uid"));
            $this->responseData['data']['total'] = $counter;
        }while(false);
        $this->output();
    }

    /**
     * @brief 我的空间可见
     * @param page 默认：1
     */
    public function getMyShowAction()
    {
        do{
            if(!$this->checkUserLogin())
            {
                $this->response->redirect("login/login");
                break;
            }
            $uid = $this->uid;
            $page = isset($this->params['page'])&&(int)$this->params['page']>0?$this->params['page']:1;
            $counter = 12;  //默认单次返回的数据数量
            $start = ($page-1)*$counter;
            $myShowList = Userfile::find(array("visible=1 and uid=$uid and file_status=".\FileStatus::NORMAL,"limit"=>$counter,'offset'=>$start,"order"=>"addtime desc"));
            $this->responseData['data']['myShowList'] = $myShowList->toArray();
            $counter = Userfile::count(array("visible=1 and uid=$uid and file_status=".\FileStatus::NORMAL));
            $this->responseData['data']['total'] = $counter;
        }while(false);
        $this->output();
    }

    /**
     * @brief 我的关注
     * @param page 默认：1
     */
    public function getMyFollowAction()
    {
        do{
            if(!$this->checkUserLogin())
            {
                $this->response->redirect("login/login");
                break;
            }
            $uid = $this->uid;
            $page = isset($this->params['page'])&&(int)$this->params['page']>0?$this->params['page']:1;
            $counter = 12;  //默认单次返回的数据数量
            $start = ($page-1)*$counter;
            $myFollowList = Userfollow::find(array("uid=$uid","limit"=>$counter,'offset'=>$start,"order"=>"id desc"));
            $UserArr = array();
            foreach($myFollowList as $k=>$myFollow){
                $UserArr[$k] = $myFollow->getTUserInfo()->toArray();
            }
            $this->responseData['data']['myFollowList'] = $UserArr;
            $counter = Userfollow::count(array("uid=$uid"));
            $this->responseData['data']['total'] = $counter;
        }while(false);
        $this->output();
    }

    /**
     * @brief 我的粉丝
     * @param page 默认：1
     */
    public function getMyFansAction()
    {
        do{
            if(!$this->checkUserLogin())
            {
                $this->response->redirect("login/login");
                break;
            }
            $uid = $this->uid;
            $page = isset($this->params['page'])&&(int)$this->params['page']>0?$this->params['page']:1;
            $counter = 12;  //默认单次返回的数据数量
            $start = ($page-1)*$counter;
            $myFansList = Userfollow::find(array("tuid=$uid","limit"=>$counter,'offset'=>$start,"order"=>"id desc"));
            $UserArr = array();
            foreach($myFansList as $k=>$myFans)
            {
                $UserArr[$k] = $myFans->getUserInfo()->toArray();
            }
            $this->responseData['data']['myFansList'] = $UserArr;
            $counter = Userfollow::count(array("tuid=$uid"));
            $this->responseData['data']['total'] = $counter;
        }while(false);
        $this->output();
    }
}