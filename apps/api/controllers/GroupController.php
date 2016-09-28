<?php
namespace Cloud\API\Controllers;
use Cloud\Models\Group;
use Cloud\Models\Groupapply;
use Cloud\Models\Groupdynamic;
use Cloud\Models\Groupuser;
use Phalcon\Validation\Validator\PresenceOf;
/**
 * @brief 群组数据接口
 */
class GroupController extends ControllerBase
{
	/**
	 * @brief 创建群组
	 * @param type 群组类型
	 * @param name 名称
	 * @param headpic 头像
	 * @param validate
	 * @param open
	 * @param intro
	 */
	public function createAction(){

		do{
			$this->validation->add('type', new PresenceOf(array('message'=>'参数缺失:type')));
			$this->validation->add('name', new PresenceOf(array('message'=>'参数缺失:name')));
			$this->validation->add('headpic', new PresenceOf(array('message'=>'参数缺失:headpic')));
			$this->validation->add('validate', new PresenceOf(array('message'=>'参数缺失:validate')));
			$this->validation->add('open', new PresenceOf(array('message'=>'参数缺失:open')));
			$this->validation->add('intro', new PresenceOf(array('message'=>'参数缺失:intro')));

			$messages = $this->validation->validate($this->params);
			if( count($messages) ){
				foreach($messages as $message) {
					array_push($this->errors, strval($message));
				}
				$this->responseData = array("code"=>\Code::ERROR_PARAMS,"msg"=>join(';', $this->errors),"line"=>__LINE__,"data"=>array());
				break;
			}

			$uid = $this->uid;
			$type = intval($this->request->getPost('type'));
			$intro = $this->request->getPost('intro');
			$headpic = $this->request->getPost('headpic');
			$validate = intval($this->request->getPost('validate'));
			$open = intval($this->request->getPost('open'));
			$name = $this->request->getPost('name');

			$this->db->begin();
			$group = new Group();
			$group->type = $type;
			$group->intro = $intro;
			$group->headpic = $headpic;
			$group->validate = $validate;
			$group->open = $open;
			$group->name = $name;
			$group->creater = $uid;
			$group->add_time = time();
			if(!$group->create()){
				$this->db->rollback();
				$this->responseData = array("code"=>\Code::ERROR,"msg"=>"数据异常","line"=>__LINE__,"data"=>array());
			}
			$groupUser = new Groupuser();
			$groupUser->gid = $group->gid;
			$groupUser->uid = $uid;
			$groupUser->user_status = 1;
			$groupUser->authority = 1;
			$groupUser->addtime = date('Y-m-d H:i:s');
			if(!$groupUser->create()){
				$this->db->rollback();
				$this->responseData = array("code"=>\Code::ERROR,"msg"=>"数据异常2","line"=>__LINE__,"data"=>array());
			}
			$this->db->commit();
		}while(false);
		$this->output();
	}

	/**
	 * @brief 我的群组列表
	 * @param 无
	 */
	public function getMyGroupAction(){
		do{
			if(!$this->checkUserLogin())
			{
				break;
			}
			$uid = $this->uid;
			//获取我的所有群组的文件
			$groupUsers = Groupuser::find(array("uid=$uid and user_status=1"));
			$myGroupIds = array();  //我的所有群组ID
			foreach($groupUsers as $groupUser)
			{
				array_push($myGroupIds,$groupUser->gid);
			}
			$groups = array();
			if(!empty($myGroupIds))
			{
				$groups = Group::find(array("gid in (".join(',',$myGroupIds).")"));
				$groups = $groups->toArray();
			}
			$this->responseData['data']['groups'] = $groups;
		}while(false);
		$this->output();
	}

	/**
	 * @brief 查找群组
	 * @param searchstr 查询的名称或者id号
	 */
	public function searchAction(){
		do{
			$this->validation->add('searchstr', new PresenceOf(array('message'=>'参数缺失:searchstr')));
			$messages = $this->validation->validate($this->params);
			if( count($messages) ){
				foreach($messages as $message) {
					array_push($this->errors, strval($message));
				}
				$this->responseData = array("code"=>\Code::ERROR_PARAMS,"msg"=>join(';', $this->errors),"line"=>__LINE__,"data"=>array());
				break;
			}

			//	查找内容
			$searchStr = $this->request->getPost('searchstr');

			$sql = "SELECT * FROM edu_group WHERE name REGEXP :name OR id=:id";
			$sth = $this->db->prepare($sql);
			$sth->bindParam(":name", $searchStr);
			$sth->bindParam(":id", intval($searchStr));

			$groupInfos = $sth->execute();
			if(!$groupInfos){
				$this->responseData = array("code"=>\Code::ERROR,"msg"=>"数据异常","line"=>__LINE__,"data"=>array());
			}

			$this->responseData['data']['groupInfos'] = $groupInfos;
		}while(false);
		$this->output();
	}

	/**
	 * @brief 更新群组信息(未开发)
	 */
	public function updateAction(){

	}

	/**
	 * @brief 申请加入群组
	 * @param gid 群组ID号
	 */
	public function applyJoinAction(){
		do{
			$this->validation->add('gid', new PresenceOf(array('message'=>'参数缺失:gid')));
			$messages = $this->validation->validate($this->params);
			if( count($messages) ){
				foreach($messages as $message) {
					array_push($this->errors, strval($message));
				}
				$this->responseData = array("code"=>\Code::ERROR_PARAMS,"msg"=>join(';', $this->errors),"line"=>__LINE__,"data"=>array());
				break;
			}

			//	gid
			$gid = intval($this->request->getPost('gid'));
			$group = Group::findFirst(
				array(
					"conditions"=>"gid=$gid"
				)
			);

			if(!$group){
				$this->logger->error("群组不存在");
				$this->responseData = array("code"=>\Code::ERROR,"msg"=>"数据异常","line"=>__LINE__,"data"=>array());
				break;
			}

			if($group->type == \GroupValidateType::NOTALLOWED){
				$this->responseData = array("code"=>\Code::ERROR,"msg"=>"该群组不允许加入","line"=>__LINE__,"data"=>array());
				break;
			}
			else if($group->type == \GroupValidateType::NONEED){
				//直接加入群组
				$groupUser = new Groupuser();
				$groupUser->gid = $gid;
				$groupUser->uid = $this->uid;
				$groupUser->authority = \GroupUserAuthority::NORMAL;
				$groupUser->add_time = time();

				if(!$groupUser->create()){
					$this->logger->error(__FILE__." [加入群组失败] ".__LINE__);
					$this->responseData = array("code"=>\Code::ERROR,"msg"=>"数据异常","line"=>__LINE__,"data"=>array());
					break;
				}
				$this->responseData['msg'] = "成功加入群组";
			}
			else{

				$groupApply = new Groupapply();
				$groupApply->gid = $gid;
				$groupApply->uid = $this->uid;
				$groupApply->add_time = time();

				if(!$groupApply->create()){
					$this->logger->error(__FILE__." [提交申请失败] ".__LINE__);
					$this->responseData = array("code"=>\Code::ERROR,"msg"=>"数据异常","line"=>__LINE__,"data"=>array());
					break;
				}
				$this->responseData['msg'] = "提交申请成功";
			}
		}while(false);
		$this->output();
	}

	/**
	 * @brief 同意加入
	 */
	public function acceptApplyAction(){
		do{
			$this->validation->add('aid', new PresenceOf(array('message'=>'参数缺失:aid')));
			$messages = $this->validation->validate($this->params);
			if( count($messages) ){
				foreach($messages as $message) {
					array_push($this->errors, strval($message));
				}
				$this->responseData = array("code"=>\Code::ERROR_PARAMS,"msg"=>join(';', $this->errors),"line"=>__LINE__,"data"=>array());
				break;
			}

			$aid = intval($this->request->getPost('aid'));
			$groupApply = Groupapply::findFirst(
				array(
					"conditions"=>"aid=$aid"
				)
			);

			if(!$groupApply){
				$this->responseData = array("code"=>\Code::ERROR,"msg"=>"申请不存在","line"=>__LINE__,"data"=>array());
				break;
			}

			if($groupApply->status != \GroupApplyStatus::APPLY){
				$this->responseData = array("code"=>\Code::ERROR,"msg"=>"该申请已经处理过了","line"=>__LINE__,"data"=>array());
				break;
			}

			$groupApply->status = \GroupApplyStatus::ACCEPT;
			$groupApply->proc_time = time();

			if(!$groupApply->save()){
				$this->logger->error(__FILE__." [同意加群申请失败] ".__LINE__);
				$this->responseData = array("code"=>\Code::ERROR,"msg"=>"数据异常","line"=>__LINE__,"data"=>array());
				break;
			}

		}while(false);
		$this->output();

	}

	/**
	 * @brief 同意加入
	 */
	public function rejectApplyAction(){
		do{
			$this->validation->add('aid', new PresenceOf(array('message'=>'参数缺失:aid')));
			$messages = $this->validation->validate($this->params);
			if( count($messages) ){
				foreach($messages as $message) {
					array_push($this->errors, strval($message));
				}
				$this->responseData = array("code"=>\Code::ERROR_PARAMS,"msg"=>join(';', $this->errors),"line"=>__LINE__,"data"=>array());
				break;
			}

			$aid = intval($this->request->getPost('aid'));
			$groupApply = Groupapply::findFirst(
				array(
					"conditions"=>"aid=$aid"
				)
			);

			if(!$groupApply){
				$this->responseData = array("code"=>\Code::ERROR,"msg"=>"申请不存在","line"=>__LINE__,"data"=>array());
				break;
			}

			if($groupApply->status != \GroupApplyStatus::APPLY){
				$this->responseData = array("code"=>\Code::ERROR,"msg"=>"该申请已经处理过了","line"=>__LINE__,"data"=>array());
				break;
			}

			$groupApply->status = \GroupApplyStatus::REJECT;
			$groupApply->proc_time = time();

			if(!$groupApply->save()){
				$this->logger->error(__FILE__." [拒绝加群申请失败] ".__LINE__);
				$this->responseData = array("code"=>\Code::ERROR,"msg"=>"数据异常","line"=>__LINE__,"data"=>array());
				break;
			}

		}while(false);
		$this->output();
	}
}