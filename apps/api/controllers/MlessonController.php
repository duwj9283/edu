<?php
namespace Cloud\API\Controllers;
use Cloud\Models\Mlesson;
use Cloud\Models\Files;
use Cloud\Models\Mlessoncomment;
use Cloud\Models\Mlessonstudy;
use Cloud\Models\Subject;
use Cloud\Models\Userfile;
use Cloud\Models\Userfileshare;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\StringLength;
use Phalcon\Validation\Validator\InclusionIn;
use Phalcon\Mvc\Model\Resultset\Simple as Resultset;

/**
 * @brief 微课数据接口
 */
class MlessonController extends ControllerBase
{
    private function timeLongFormat($file){
        $time = exec('ffprobe -v error -show_entries format=duration -of default=noprint_wrappers=1:nokey=1 '.$file);
        $min = (int)($time/60);
        if($min<10){
            $min = '0'.$min;
        }
        $sec = (int)$time%60;
        if($sec<10){
            $sec = '0'.$sec;
        }
        return $min.":".$sec;
    }

    /**
     * @brief 获取个人微课列表
     * @param page (选填) 当前页 默认是：1
     */
    public function getMyMlessonsAction()
    {
        do {
            if(!$this->checkUserLogin())
            {
                break;
            }
            $uid = $this->uid;
            $page = isset($this->params['page'])&&(int)$this->params['page']>0?$this->params['page']:1;
            $limit = 12;
            $offset = ($page-1)*$limit;
            $mLessons = Mlesson::find(array("uid=$uid","order"=>"addtime desc","offset"=>$offset,"limit"=>$limit));
            $mLessonArr = array();
            foreach($mLessons as $k=>$mLesson)
            {
                $file = Userfile::findFirst(array("id=".$mLesson->file));
                $filepool = Files::findFirst(array("id=".$file->file_id));
                $fileUrl = $this->config->upload_filepool.$filepool->path.'/'.$filepool->md5;
                $mLessonArr[$k] = $mLesson->toArray();
                $mLessonArr[$k]['timeLone'] = $this->timeLongFormat($fileUrl);
            }
            $this->responseData['data']['mLessons'] = $mLessonArr;
            $count = Mlesson::count(array("uid=$uid"));
            $this->responseData['data']['count'] = $count;
        }while(false);
        $this->output();
    }

    /**
     * @brief 获取个人微课学习列表
     * @param page (选填) 当前页 默认是：1
     */
    public function getMlessonStudyAction()
    {
        do {
            if(!$this->checkUserLogin())
            {
                break;
            }
            $uid = $this->uid;
            $page = isset($this->params['page'])&&(int)$this->params['page']>0?$this->params['page']:1;
            $limit = 12;
            $offset = ($page-1)*$limit;
            $mLessonStudys = Mlessonstudy::find(array("uid=$uid","order"=>"addtime desc","offset"=>$offset,"limit"=>$limit));
            $mLessonArr = array();
            foreach($mLessonStudys as $k=>$mLessonStudy)
            {
                $mLessonArr[$k]['userinfo'] = $mLessonStudy->getUserinfo()->toArray();
                $mLesson = $mLessonStudy->getMlesson();
                $file = Userfile::findFirst(array("id=".$mLesson->file));
                $filepool = Files::findFirst(array("id=".$file->file_id));
                $fileUrl = $this->config->upload_filepool.$filepool->path.'/'.$filepool->md5;
                $mLessonArr[$k]['mlesson'] = $mLesson->toArray();
                $mLessonArr[$k]['timeLone'] = $this->timeLongFormat($fileUrl);
            }
            $this->responseData['data']['mLessons'] = $mLessonArr;
            $count = Mlessonstudy::count(array("uid=$uid"));
            $this->responseData['data']['count'] = $count;
        }while(false);
        $this->output();
    }

    /**
     * @brief 添加微课
     * @param title 微课名称
     * @param file 课件
     * @param（选填）file_ids 相关资料 （数组）
     * @param pic 微课封面
     * @param desc 微课简介
     * @param type 发布范围: 1：公开 2：群组 3：私有（公开含密码）
     * @param（选填）password type=3时，必传
     * @param subject_id 专业ID
     */
    public function addMlessonAction()
    {
//        $this->params = array('title'=>'test','pic'=>'test','file'=>45,'desc'=>'测试','type'=>1);
        do {
            $this->validation->add('title', new PresenceOf(array('message'=>'参数缺失:title')));
            $this->validation->add('subject_id', new PresenceOf(array('message'=>'参数缺失:subject_id')));
            $this->validation->add('pic', new PresenceOf(array('message'=>'参数缺失:pic')));
            $this->validation->add('file', new PresenceOf(array('message'=>'参数缺失:file')));
            $this->validation->add('desc', new PresenceOf(array('message'=>'参数缺失:desc')));
            $this->validation->add('type', new InclusionIn(array('message'=>"参数type值只能为:1/2/3",
                'domain'=>array('1', '2', '3'),
                'allowEmpty'=>false)));
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
            $title = $this->params['title'];
            $file = $this->params['file'];
            $pic = $this->params['pic'];
            $label = array();
            $desc = $this->params['desc'];
            $file_ids = isset($this->params['file_ids'])?$this->params['file_ids']:array();
            $password = isset($this->params['password'])?$this->params['password']:'';
            $type = (int)$this->params['type'];
            $subject_id = (int)$this->params['subject_id'];
            if(!empty($file_ids)&&!is_array($file_ids))
            {
                $this->responseData = array("code"=>\Code::ERROR_PARAMS,"msg"=>'file_ids必须为数组格式',"line"=>__LINE__,"data"=>array());
                break;
            }
            $this->db->begin();
            $mLesson = new Mlesson();
            $mLesson->title = $title;
            $mLesson->uid = $uid;
            $mLesson->file = $file;
            $mLesson->pic = $pic;
            $mLesson->subject_id = $subject_id;
            if(!empty($label))
            {
                $mLesson->label = join(',',$label);
            }
            if(!empty($file_ids))
            {
                $mLesson->file_ids = join(',',$file_ids);
                foreach($file_ids as $file_id){
                    $userFileShare = Userfileshare::findFirst(array("uid=$uid and user_file_id=$file_id"));
                    if(!$userFileShare)
                    {
                        $userFileShare = new Userfileshare();
                        $userFileShare->uid = $uid;
                        $userFileShare->user_file_id = $file_id;
                        if(!$userFileShare->create())
                        {
                            $this->db->rollback();
                            break;
                        }
                    }
                }
            }
            if(!empty($password)&&$type==3)
            {
                $mLesson->password = $password;
            }
            $mLesson->desc = $desc;
            $mLesson->type = $type;
            if(!$mLesson->create())
            {
                $this->db->rollback();
                $this->responseData = array("code"=>\Code::ERROR_PARAMS,"msg"=>'微课添加失败',"line"=>__LINE__,"data"=>array());
                break;
            }
            $this->db->commit();
            $this->responseData['data']['m_lesson_id'] = $mLesson->id;
        }while(false);
        $this->output();
    }

    /**
     * @brief 修改微课
     * @param m_lesson_id 微课ID
     * @param title 微课名称
     * @param file 课件
     * @param（选填）file_ids 相关资料 （数组）
     * @param desc 微课简介
     * @param type 发布范围: 1：公开 2：群组 3：私有（公开含密码）
     * @param（选填）password type=3时，必传
     * @param subject_id 专业ID
     */
    public function editMlessonAction()
    {
//        $this->params = array('m_lesson_id'=>69,'title'=>'test1','pic'=>'test1','file'=>46,'desc'=>'测试1','type'=>1);
        do {
            $this->validation->add('m_lesson_id', new PresenceOf(array('message'=>'参数缺失:m_lesson_id')));
            $this->validation->add('title', new PresenceOf(array('message'=>'参数缺失:title')));
            $this->validation->add('file', new PresenceOf(array('message'=>'参数缺失:file')));
            $this->validation->add('desc', new PresenceOf(array('message'=>'参数缺失:desc')));
            $this->validation->add('type', new InclusionIn(array('message'=>"参数type值只能为:1/2/3",
                'domain'=>array('1', '2', '3'),
                'allowEmpty'=>false)));
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
            $m_lesson_id = $this->params['m_lesson_id'];
            $title = $this->params['title'];
            $file = $this->params['file'];
            $pic = isset($this->params['pic'])?$this->params['pic']:'';
            $label = array();
            $desc = $this->params['desc'];
            $file_ids = isset($this->params['file_ids'])?$this->params['file_ids']:array();
            $password = isset($this->params['password'])?$this->params['password']:'';
            $type = (int)$this->params['type'];
            $subject_id = (int)$this->params['subject_id'];
            if(!empty($lables)&&!is_array($label))
            {
                $this->responseData = array("code"=>\Code::ERROR_PARAMS,"msg"=>'label必须为数据格式',"line"=>__LINE__,"data"=>array());
                break;
            }
            $mLesson = Mlesson::findFirst(array("id = $m_lesson_id and uid = $uid"));
            if(!$mLesson)
            {
                $this->responseData = array("code"=>\Code::ERROR,"msg"=>'微课不存在',"line"=>__LINE__,"data"=>array());
                break;
            }
            $mLesson->title = $title;
            $mLesson->uid = $uid;
            $mLesson->file = $file;
            $mLesson->subject_id = $subject_id;
            if(!empty($pic))
            {
                $mLesson->pic = $pic;
            }
            if(!empty($label))
            {
                $mLesson->label = join(',',$label);
            }
            if(!empty($file_ids))
            {
                $mLesson->file_ids = join(',',$file_ids);
            }
            if(!empty($password)&&$type==3)
            {
                $mLesson->password = $password;
            }
            $mLesson->desc = $desc;
            $mLesson->type = $type;
            if(!$mLesson->update())
            {
                $this->responseData = array("code"=>\Code::ERROR_PARAMS,"msg"=>'微课修改失败',"line"=>__LINE__,"data"=>array());
                break;
            }
            $this->responseData['data']['m_lesson_id'] = $mLesson->id;
        }while(false);
        $this->output();
    }

    /**
     * @brief 删除微课
     * @param m_lesson_id 微课ID
     */
    public function delMlessonAction()
    {
        do {
            $this->validation->add('m_lesson_id', new PresenceOf(array('message'=>'参数缺失:m_lesson_id')));
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
            $m_lesson_id = (int)$this->params['m_lesson_id'];
            $mLesson = Mlesson::findFirst(array("uid=$uid and id=$m_lesson_id"));
            if(!$mLesson)
            {
                $this->responseData = array("code"=>\Code::ERROR_PARAMS,"msg"=>'微课不存在',"line"=>__LINE__,"data"=>array());
                break;
            }
            $this->db->begin();
            if(!$mLesson->delete())
            {
                $this->db->rollback();
                $this->responseData = array("code"=>\Code::ERROR_PARAMS,"msg"=>'删除微课失败',"line"=>__LINE__,"data"=>array());
                break;
            }
            $sql = "delete from edu_m_lesson_study where m_lesson_id = $m_lesson_id";
            $query = $this->modelsManager->createQuery($sql);
            $result = $query->execute();
            if(!$result->success())
            {
                $this->db->rollback();
                $this->responseData = array("code"=>\Code::ERROR_PARAMS,"msg"=>'删除微课学习失败',"line"=>__LINE__,"data"=>array());
                break;
            }
            $this->db->commit();
        }while(false);
        $this->output();
    }

    /**
     * @brief 获取微课
     * @param m_lesson_id 微课ID
     */
    public function getMlessonAction()
    {
//        $this->params = array('m_lesson_id'=>68);
        do {
            $this->validation->add('m_lesson_id', new PresenceOf(array('message'=>'参数缺失:m_lesson_id')));
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
            $m_lesson_id = (int)$this->params['m_lesson_id'];
            $mLesson = Mlesson::findFirst(array("uid=$uid and id=$m_lesson_id"));
            if(!$mLesson)
            {
                $this->responseData = array("code"=>\Code::ERROR_PARAMS,"msg"=>'微课不存在',"line"=>__LINE__,"data"=>array());
                break;
            }
            $subject = Subject::findFirst(array("id=".$mLesson->subject_id));
            $mLesson = $mLesson->toArray();
            $mLesson['subject_father_id'] = $subject->father_id;
            $this->responseData['data']['m_lesson'] = $mLesson;
        }while(false);
        $this->output();
    }

    /**
     * @brief 评价微课
     * @param m_lesson_id 微课ID
     * @param ref_uid 回复某个人的ID,非回复则为0
     * @param ref_id 回复的评论ID(请注意与lesson_id的区别,每个微课有个ID,每个评论也有个ID,这个是评论的ID),非回复则为0
     * @param content 评论的内容
     */
    public function commentLessonAction()
    {
//        $this->params = array('lesson_id'=>1,'ref_uid'=>0,'ref_id'=>0,'content'=>'这个微课好好\(^o^)/~');
        do {
            $this->validation->add('m_lesson_id', new PresenceOf(array('message'=>'参数缺失:m_lesson_id')));
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
            $m_lesson_id = (int)$this->params['m_lesson_id'];
            $ref_uid = (int)$this->params['ref_uid'];
            $ref_id = (int)$this->params['ref_id'];
            $content = $this->params['content'];
            $lesson = Mlesson::findFirst(array("id=$m_lesson_id and status=1"));
            if(!$lesson)
            {
                $this->responseData = array("code"=>\Code::ERROR_PARAMS,"msg"=>'微课可能已经被删除',"line"=>__LINE__,"data"=>array());
                break;
            }
            $lessonComment = new Mlessoncomment();
            $lessonComment->uid = $uid;
            $lessonComment->m_lesson_id = $m_lesson_id;
            $lessonComment->content = $content;
            $lessonComment->ref_id = $ref_id;
            $lessonComment->ref_uid = $ref_uid;
            $lessonComment->create_time = date('Y-m-d H:i:s');
            if(!$lessonComment->create())
            {
                $this->responseData = array("code"=>\Code::ERROR_DB,"msg"=>'微课评价失败',"line"=>__LINE__,"data"=>array());
                break;
            }
        }while(false);
        $this->output();
    }

    /**
     * @brief 微课评论
     * @param m_lesson_id 微课ID
     */
    public function getMlessonDetailAction()
    {
//        $this->params = array('lesson_id'=>1);
        do {
            $this->validation->add('m_lesson_id', new PresenceOf(array('message'=>'参数缺失:m_lesson_id')));
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
            $m_lesson_id = $this->params['m_lesson_id'];
            //微课评价
            $lessonComments = Mlessoncomment::find(array("m_lesson_id=$m_lesson_id"));
            $lessonInfos = array();
            $comment_count = 0;
            foreach($lessonComments as $lessonComment){
                $lesson_comment_info = $lessonComment->toArray();
                if( $lessonComment->ref_uid == 0 ){
                    $lesson_comment_info['refUserName'] = "";
                    $lesson_comment_info['refHeadpic'] = array();
                }
                else{
                    $refUserInfo = $lessonComment->getRefUserInfo();
                    $lesson_comment_info['refUserName'] = $refUserInfo->nick_name;
                    $lesson_comment_info['refHeadpic'] = $refUserInfo->headpic;
                }
                $userInfo = $lessonComment->getUserInfo();
                $lesson_comment_info['userName'] = $userInfo->nick_name;
                $lesson_comment_info['headpic'] = $userInfo->headpic;

                if($lessonComment->ref_id == 0 ){
                    $comment_count++;
                    $lessonInfos[$lessonComment->id][] = $lesson_comment_info;
                }
                else
                {
                    $lessonInfos[$lessonComment->ref_id][] = $lesson_comment_info;
                }
            }
            $this->responseData['data']['comment_list'] = $lessonInfos;
            //评论数量
            $this->responseData['data']['comment_count'] = $comment_count;
        }while(false);
        $this->output();
    }

    /**
     * @brief 微课列表
     * @param (选填) page  当前页 默认是：1
     * @param (选填) subjectID 子集科目ID
     * @param (选填) keywords 关键词
     */
    public function getMlessonListAction()
    {
        do {
            $page = isset($this->params['page'])&&(int)$this->params['page']>0?$this->params['page']:1;
            $subjectID = isset($this->params['subjectID'])&&(int)$this->params['subjectID']>0?$this->params['subjectID']:0;
            $keywords = isset($this->params['keywords'])?$this->params['keywords'] : '';
            $limit = 12;
            $offset = ($page-1)*$limit;
            $condition = '';
            if($subjectID>0)
            {
                $condition .= " and subject_id = $subjectID";
            }
            if($keywords)
            {
                $condition .= " and title like '%$keywords%'";
            }
            $mLessons = Mlesson::find(array("status=1 $condition","order"=>"addtime desc","offset"=>$offset,"limit"=>$limit));
            $mLessonArr = array();
            $subjectIds = array();
            foreach($mLessons as $k=>$mLesson)
            {
                if(!in_array($mLesson->subject_id,$subjectIds))
                {
                    array_push($subjectIds,$mLesson->subject_id);
                }
                $studyCount = Mlessonstudy::count(array("m_lesson_id=".$mLesson->id));
                $mLessonArr[$k] = $mLesson->toArray();
                $file = Userfile::findFirst(array("id=".$mLesson->file));
                $filepool = Files::findFirst(array("id=".$file->file_id));
                $fileUrl = $this->config->upload_filepool.$filepool->path.'/'.$filepool->md5;
                $mLessonArr[$k]['timeLone'] = $this->timeLongFormat($fileUrl);
                $mLessonArr[$k]['userinfo'] = $mLesson->getUserinfo()->toArray();
                $mLessonArr[$k]['count'] = $studyCount;
                $mLessonArr[$k]['subject_name'] = '';
            }
            if(!empty($subjectIds))
            {
                $subjects = Subject::find(array('id in ('.join(',',$subjectIds).')'));
                $subjectArr = array();
                foreach($subjects as $subject)
                {
                    $subjectArr[$subject->id] = $subject->subject_name;
                }
                foreach($mLessons as $k=>$mLesson)
                {
                    $mLessonArr[$k]['subject_name'] = $subjectArr[$mLesson->subject_id];
                }
            }
            $this->responseData['data']['mLessons'] = array_values($mLessonArr);
            $counter = Mlesson::count(array("status=1 $condition"));
            $this->responseData['data']['total'] = $counter;
        }while(false);
        $this->output();
    }

    /**
     * @brief 参加学习
     * @param m_lesson_id 微课Id
     */
    public function inMlessonAction()
    {
//        $this->params = array("live_id"=>42);
        do {
            $this->validation->add('m_lesson_id', new PresenceOf(['message' => '参数缺失:m_lesson_id']));
            $messages = $this->validation->validate($this->params);
            if (count($messages)) {
                foreach ($messages as $message) {
                    array_push($this->errors, strval($message));
                }
                $this->responseData = ['code' => \Code::ERROR_PARAMS, 'msg' => join(';', $this->errors), 'line' => __LINE__, 'data' => []];
                break;
            }
            if (!$this->checkUserLogin()) {
                break;
            }
            $uid = $this->uid;
            $m_lesson_id = intval($this->params['m_lesson_id']);
            $mlesson = Mlesson::findFirst(array("id=$m_lesson_id"));
            if (!$mlesson) {
                $this->responseData = ['code' => \Code::ERROR, 'msg' => '微课不存在', 'line' => __LINE__, 'data' => []];
                break;
            }
            $mLessonstudy = Mlessonstudy::findFirst(array("uid=$uid and m_lesson_id=$m_lesson_id"));
            if(!$mLessonstudy&&$mLessonstudy->uid!=$uid)
            {
                $mLessonstudy = new Mlessonstudy();
                $mLessonstudy->uid = $uid;
                $mLessonstudy->m_lesson_id = $m_lesson_id;
                if(!$mLessonstudy->create())
                {
                    $this->responseData = ['code' => \Code::ERROR_DB, 'msg' => '微课计数失败', 'line' => __LINE__, 'data' => []];
                    break;
                }
            }
        } while (false);
        $this->output();
    }

    /**
     * @brief 删除微课学习（学生）
     * @param id 微课学习ID
     */
    public function delMlessonStudyAction()
    {
        do {
            $this->validation->add('id', new PresenceOf(array('message'=>'参数缺失:m_lesson_id')));
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
            $id = (int)$this->params['id'];
            $mLessonStudy = Mlessonstudy::findFirst(array("uid=$uid and id=$id"));
            if(!$mLessonStudy)
            {
                $this->responseData = array("code"=>\Code::ERROR_PARAMS,"msg"=>'微课学习不存在',"line"=>__LINE__,"data"=>array());
                break;
            }
            if(!$mLessonStudy->delete())
            {
                $this->responseData = array("code"=>\Code::ERROR_PARAMS,"msg"=>'删除微课学习失败',"line"=>__LINE__,"data"=>array());
                break;
            }
        }while(false);
        $this->output();
    }
}