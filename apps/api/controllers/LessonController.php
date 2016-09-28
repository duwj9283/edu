<?php
namespace Cloud\API\Controllers;
use Cloud\Models\Lesson;
use Cloud\Models\Lessonpay;
use Cloud\Models\Share;
use Cloud\Models\Subject;
use Cloud\Models\Userfile;
use Cloud\Models\Lessonlist;
use Cloud\Models\Lessonstudy;
use Cloud\Models\Lessonask;
use Cloud\Models\Lessonnote;
use Cloud\Models\Lessoncomment;
use Cloud\Models\Question;
use Cloud\Models\Questionitem;
use Cloud\Models\Userquestion;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\StringLength;
use Phalcon\Validation\Validator\InclusionIn;
use Phalcon\Mvc\Model\Resultset\Simple as Resultset;

/**
 * @brief 课程数据接口
 */
class LessonController extends ControllerBase
{
    /**
     * @brief 获取个人课程列表
     * @param page 默认 1
     */
    public function getMyLessonsAction()
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
            $lessons = Lesson::find(array("uid=$uid","order"=>"addtime desc","offset"=>$offset,"limit"=>$limit));
            $lessonIds = array();
            $lesson_arr = array();
            foreach($lessons as $lesson)
            {
                array_push($lessonIds,$lesson->id);
                $lesson_arr[$lesson->id] = $lesson->toArray();
                $lesson_arr[$lesson->id]['ask_count'] = 0;

                //最新章节
                $lesson_lists = Lessonlist::find(array("lesson_id=".$lesson->id,"order"=>"sort asc"));
                $lesson_list_array = array();
                foreach($lesson_lists as $lesson_list)
                {
                    if($lesson_list->path=='/')
                    {
                        $lesson_list_array[$lesson_list->path.$lesson_list->name.'/']['lesson'] = $lesson_list->toArray();
                }
                    else
                    {
                        $lesson_list_array[$lesson_list->path]['child_list'][] = $lesson_list->toArray();
                    }
                }
                $lesson_list_array = $this->arraySort($lesson_list_array,'sort');
                $lesson_list_ids = array();
                foreach($lesson_list_array as $list)
                {
                    foreach($list['child_list'] as $k=>$v)
                    {
                        array_push($lesson_list_ids,$v['id']);
                    }
                }
                $listInfo = array();
                if(!empty($lesson_list_ids))
                {
                    $last_list_id = $lesson_list_ids[count($lesson_list_ids)-1];
                    $listInfo = Lessonlist::findFirst(array("id=$last_list_id"));
                    $listInfo = $listInfo->toArray();
                }
                $lesson_arr[$lesson->id]['last_list'] = $listInfo;
//                $lesson_arr[$lesson->id]['question_count'] = 0;
            }
            if(!empty($lessonIds))
            {
                //课程分享数量统计
//                $sql = "select addition,count(DISTINCT uid) as c from edu_share where type=".\ShareType::LESSON." and addition in (".join(',',$lessonIds).")";
//                $lessonShare = new Share();
//                $lessonShare = new Resultset(null, $lessonShare, $lessonShare->getReadConnection()->query($sql));
//                foreach($lessonShare as $share)
//                {
//                    if(!empty($share->addition))
//                    {
//                        $lesson_arr[$share->addition]['share_count'] += $share->c;
//                    }
//                }
                //课程提问数量统计
                $sql = "select lesson_id,count(DISTINCT uid) as c from edu_lesson_ask where ref_id=0 and del=0 and lesson_id in (".join(',',$lessonIds).")";
                $lessonAsk = new Lessonask();
                $lessonAsk = new Resultset(null, $lessonAsk, $lessonAsk->getReadConnection()->query($sql));
                foreach($lessonAsk as $ask)
                {
                    if(!empty($ask->lesson_id))
                    {
                        $lesson_arr[$ask->lesson_id]['ask_count'] += $ask->c;
                    }
                }
                //课程习题数量统计
//                $sql = "select lesson_id,question_ids from edu_lesson_list where lesson_id in (".join(',',$lessonIds).")";
//                $lessonList = new Lessonlist();
//                $lessonList = new Resultset(null, $lessonList, $lessonList->getReadConnection()->query($sql));
//                $question_ids = array();
//                foreach($lessonList as $question)
//                {
//                    if(!empty($question->question_ids))
//                    {
//                        $question_id_arr = explode(',',$question->question_ids);
//                        foreach($question_id_arr as $question_id)
//                        {
//                            if(!in_array($question_id,$question_ids))
//                            {
//                                array_push($question_ids,$question_id);
//                            }
//                        }
//                        $lesson_arr[$question->lesson_id]['question_count'] = count($question_ids);
//                    }
//                }
            }
            $this->responseData['data']['lessons'] = $lesson_arr;
            $total = Lesson::count(array("uid=$uid"));
            $this->responseData['data']['total'] = $total;
        }while(false);
        $this->output();
    }

    /**
     * @brief 添加课程
     * @param title 课程名称
     * @param （选填）subtitle 课程副标题
     * @param pic 课程封面
     * @param （选填）label 课程标签 （数组）
     * @param （选填）desc 课程简介
     * @param （选填）description 课程描述
     * @param price 课程价格
     * @param father_subject_id 专业ID
     * @param subject_id 专业ID
     * @param type 课程类型 1:非连载课程 2：连载中 3：完结课程
     */
    public function addLessonAction()
    {
        do {
            $this->validation->add('title', new PresenceOf(array('message'=>'参数缺失:title')));
            $this->validation->add('pic', new PresenceOf(array('message'=>'参数缺失:pic')));
            $this->validation->add('price', new PresenceOf(array('message'=>'参数缺失:price')));
            $this->validation->add('type', new InclusionIn(array('message'=>"参数type值只能为:1/2/3",
                'domain'=>array('1', '2', '3'),
                'allowEmpty'=>false)));
            $this->validation->add('father_subject_id', new PresenceOf(array('message'=>'参数缺失:father_subject_id')));
            $this->validation->add('subject_id', new PresenceOf(array('message'=>'参数缺失:subject_id')));
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
            $subtitle = $this->params['subtitle'];
            $pic = $this->params['pic'];
            $label = isset($this->params['label'])?$this->params['label']:array();
            $desc = $this->params['desc'];
            $description = $this->params['description'];
            $price = (int)$this->params['price'];
            $type = (int)$this->params['type'];
            $father_subject_id = (int)$this->params['father_subject_id'];
            $subject_id = (int)$this->params['subject_id'];
            if(!empty($lables)&&!is_array($label))
            {
                $this->responseData = array("code"=>\Code::ERROR_PARAMS,"msg"=>'label必须为数据格式',"line"=>__LINE__,"data"=>array());
                break;
            }
            $lesson = new Lesson();
            $lesson->title = $title;
            $lesson->subtitle = $subtitle;
            $lesson->uid = $uid;
            $lesson->pic = $pic;
            $lesson->label = join(',',$label);
            $lesson->desc = $desc;
            $lesson->description = $description;
            $lesson->price = $price;
            $lesson->type = $type;
            $lesson->subject_id = $subject_id;
            $lesson->father_subject_id = $father_subject_id;
            if(!$lesson->create())
            {
                $this->responseData = array("code"=>\Code::ERROR_PARAMS,"msg"=>'课程添加失败',"line"=>__LINE__,"data"=>array());
                break;
            }
            $this->responseData['data']['lesson_id'] = $lesson->id;
        }while(false);
        $this->output();
    }

    /**
     * @brief 修改课程
     * @param lesson_id 课程ID
     * @param title 课程名称
     * @param （选填）subtitle 课程副标题
     * @param pic 课程封面
     * @param （选填）label 课程标签 （数组）
     * @param （选填）desc 课程简介
     * @param （选填）description 课程描述
     * @param price 课程价格
     * @param subject_id 专业ID
     * @param type 课程类型 （默认）1:非连载课程 2：连载中 3：完结课程
     */
    public function editLessonAction()
    {
        do {
            $this->validation->add('lesson_id', new PresenceOf(array('message'=>'参数缺失:lesson_id')));
            $this->validation->add('title', new PresenceOf(array('message'=>'参数缺失:title')));
            $this->validation->add('pic', new PresenceOf(array('message'=>'参数缺失:pic')));
            $this->validation->add('price', new PresenceOf(array('message'=>'参数缺失:price')));
            $this->validation->add('type', new InclusionIn(array('message'=>"参数值只能为:1/2/3",
                'domain'=>array('1', '2', '3'),
                'allowEmpty'=>false)));
            $this->validation->add('father_subject_id', new PresenceOf(array('message'=>'参数缺失:father_subject_id')));
            $this->validation->add('subject_id', new PresenceOf(array('message'=>'参数缺失:subject_id')));
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
            $lesson_id = $this->params['lesson_id'];
            $title = $this->params['title'];
            $subtitle = isset($this->params['subtitle'])?$this->params['subtitle']:'';
            $pic = $this->params['pic'];
            $label = isset($this->params['label'])?$this->params['label']:array();
            $desc = $this->params['desc'];
            $description = isset($this->params['description'])?$this->params['description']:'';
            $price = (int)$this->params['price'];
            $type = (int)$this->params['type'];
            $subject_id = (int)$this->params['subject_id'];
            $father_subject_id = (int)$this->params['father_subject_id'];
            if(!empty($lables)&&!is_array($label))
            {
                $this->responseData = array("code"=>\Code::ERROR_PARAMS,"msg"=>'label必须为数据格式',"line"=>__LINE__,"data"=>array());
                break;
            }
            $lesson = Lesson::findFirst(array("id = $lesson_id and uid = $uid"));
            if(!$lesson)
            {
                $this->responseData = array("code"=>\Code::ERROR,"msg"=>'课程不存在',"line"=>__LINE__,"data"=>array());
                break;
            }
            if(!empty($subtitle))
            {
                $lesson->subtitle = $subtitle;
            }
            if(!empty($desc))
            {
                $lesson->desc = $desc;
            }
            $lesson->title = $title;
            $lesson->uid = $uid;
            $lesson->pic = $pic;
            $lesson->label = join(',',$label);
            $lesson->price = $price;
            $lesson->type = $type;
            $lesson->subject_id = $subject_id;
            $lesson->father_subject_id = $father_subject_id;
            $lesson->description = $description;
            if(!$lesson->update())
            {
                $this->responseData = array("code"=>\Code::ERROR_PARAMS,"msg"=>'课程修改失败',"line"=>__LINE__,"data"=>array());
                break;
            }
            $this->responseData['data']['lesson_id'] = $lesson->id;
        }while(false);
        $this->output();
    }

    /**
     * @brief 获取课程
     * @param @param lesson_id 课程ID
     */
    public function getLessonAction()
    {
        do {
            $this->validation->add('lesson_id', new PresenceOf(array('message'=>'参数缺失:lesson_id')));
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
            $lesson_id = (int)$this->params['lesson_id'];
            $lesson = Lesson::findFirst(array("uid=$uid and id=$lesson_id"));
            if(!$lesson)
            {
                $this->responseData = array("code"=>\Code::ERROR_PARAMS,"msg"=>'课程不存在',"line"=>__LINE__,"data"=>array());
                break;
            }
            $this->responseData['data']['lesson'] = $lesson->toArray();
        }while(false);
        $this->output();
    }

    /**
     * @brief 添加/修改课程章节
     * @param lesson_id 课程ID
     * @param lesson_list 章列表
     * @param (键)id 章ID
     * @param (键)name 章名称
     * @param (键)path 章路径
     * @param (键)sort 章排序
     * @param (键)child_list 该章节下的节点列表 例子：array(array(name=>'',path=>'',file=>'',file_ids=>array(),question_ids=>array(),sort=>''),array(),...)
     */
    public function addLessonListAction()
    {
//        $this->params = array('lesson_id'=>1,'lesson_list'=>array(
//            array('id'=>46,'name'=>'一','path'=>'/','sort'=>'1','child_list'=>
//                array(
//                    array('id'=>58,'name'=>'1-1','path'=>'/一/','sort'=>'1','file'=>1,'file_ids'=>array(1,2,3),'question_ids'=>array(1)),
//                    array('id'=>59,'name'=>'1-2','path'=>'/一/','sort'=>'2','file'=>1,'file_ids'=>array(4,5,6),'question_ids'=>array(1)),
//                    array('id'=>60,'name'=>'1-3','path'=>'/一/','sort'=>'3','file'=>1,'file_ids'=>array(7,8,9),'question_ids'=>array(1)),
//                    array('id'=>61,'name'=>'1-4','path'=>'/一/','sort'=>'4','file'=>1,'file_ids'=>array(111,211,311),'question_ids'=>array(1))
//                )
//            ),
//            array('id'=>50,'name'=>'二','path'=>'/','sort'=>'2','child_list'=>
//                array(
//                    array('id'=>51,'name'=>'2-1','path'=>'/二/','sort'=>'1','file'=>1,'file_ids'=>array(11,21,31)),
//                    array('id'=>131,'name'=>'2-2','path'=>'/二/','sort'=>'1','file'=>1,'file_ids'=>array(11,21,31)),
//                    array('id'=>53,'name'=>'2-3','path'=>'/二/','sort'=>'3','file'=>1,'file_ids'=>array(11,21,31)),
//                )
//            ),
//            array('id'=>54,'name'=>'三','path'=>'/','sort'=>'3','child_list'=>
//                array(
//                    array('id'=>55,'name'=>'3-1','path'=>'/三/','sort'=>'1','file'=>1,'file_ids'=>array(111,211,311)),
//                    array('id'=>56,'name'=>'3-2','path'=>'/三/','sort'=>'2','file'=>1,'file_ids'=>array(111,211,311)),
//                    array('id'=>57,'name'=>'3-3','path'=>'/三/','sort'=>'3','file'=>1,'file_ids'=>array(111,211,311)),
//                )
//            ),
//            array('id'=>62,'name'=>'四','path'=>'/','sort'=>'4','child_list'=>
//                array(
//                    array('id'=>63,'name'=>'4-1','path'=>'/四/','sort'=>'1','file'=>1,'file_ids'=>array(111,211,311)),
//                    array('id'=>64,'name'=>'4-2','path'=>'/四/','sort'=>'2','file'=>1,'file_ids'=>array(111,211,311)),
//                    array('id'=>65,'name'=>'4-3','path'=>'/四/','sort'=>'3','file'=>1,'file_ids'=>array(111,211,311)),
//                )
//            )
//        ));
        do {
            $this->validation->add('lesson_id', new PresenceOf(array('message'=>'参数缺失:lesson_id')));
            $this->validation->add('lesson_list', new PresenceOf(array('message'=>'参数缺失:lesson_list')));
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
            $uid = (int)$this->uid;
            $lesson_id = (int)$this->params['lesson_id'];
            $lesson_list = $this->params['lesson_list'];

            $lesson = Lesson::findFirst(array("id=$lesson_id and uid=$uid"));
            if(!$lesson)
            {
                $this->responseData = array("code"=>\Code::ERROR_DB,"msg"=>'课程不存在',"line"=>__LINE__,"data"=>array());
                break;
            }
            $rootArr = array();
            foreach($lesson_list as $val)
            {
                array_push($rootArr,$val['name']);
            }
            if(count(array_unique($rootArr))!=count($rootArr))
            {
                $this->responseData = array("code"=>\Code::ERROR_DB,"msg"=>'章节不能重名',"line"=>__LINE__,"data"=>array());
                break;
            }
            $addLessonListSql = "INSERT INTO edu_lesson_list(id,lesson_id,name,path,sort,file,file_ids,question_ids) VALUES";
            $lesson_list_ids = array();
            $relationFile = array();    //关联资料文件
            $courseFile = array();    //课件文件
            foreach($lesson_list as $k=>$v)
            {
                $addLessonListSql .="(".$v['id'].",$lesson_id,'".$v['name']."','".$v['path']."',".$v['sort'].",0,'',''),";
                if(!empty($v['child_list']))
                {
                    foreach($v['child_list'] as $kk=>$vv)
                    {
                        array_push($lesson_list_ids,$vv['id']);

                        $file_ids = "";
                        if(!empty($vv['file_ids']))
                        {
                            foreach($vv['file_ids'] as $file)
                            {
                                if(!in_array($file,$relationFile))
                                {
                                    array_push($relationFile,$file);
                                }
                            }
                            $file_ids = join(',',$vv['file_ids']);
                        }
                        $question_ids = "";
                        if(!empty($vv['question_ids']))
                        {
                            $question_ids = join(',',$vv['question_ids']);
                        }
                        if(!in_array($vv['file'],$courseFile))
                        {
                            array_push($courseFile,$vv['file']);
                        }
                        $addLessonListSql .="(".$vv['id'].",$lesson_id,'".$vv['name']."','".$vv['path']."',".$vv['sort'].",".$vv['file'].",'".$file_ids."','".$question_ids."'),";
                    }
                }
            }
            $duplicate = " ON DUPLICATE KEY UPDATE name=VALUES(name),path=VALUES(path),file=VALUES(file),file_ids=VALUES(file_ids),question_ids=VALUES(question_ids),sort=VALUES(sort)";
            $lesson_list_dbs = Lessonlist::find(array("lesson_id=$lesson_id"));
            $lesson_list_db_ids = array();
            foreach($lesson_list_dbs as $lesson_list_db)
            {
                array_push($lesson_list_db_ids,$lesson_list_db->id);
            }
            $diff = array_diff($lesson_list_db_ids,$lesson_list_ids);
            if(!empty($diff))
            {
                $delSql = "delete from edu_lesson_list where id in(".join(',',$diff).")";
                $del = $this->db->query($delSql);
                if(!$del)
                {
                    $this->responseData = array("code"=>\Code::ERROR_DB,"msg"=>'添加章节失败2',"line"=>__LINE__,"data"=>array());
                    break;
                }
            }
            $addLessonListSql = substr($addLessonListSql,0,strlen($addLessonListSql)-1);
            $add = $this->db->query($addLessonListSql.$duplicate);
            if(!$add)
            {
                $this->responseData = array("code"=>\Code::ERROR_DB,"msg"=>'添加章节失败',"line"=>__LINE__,"data"=>array());
                break;
            }
            //查询创建服务器课程相关文件地址
            $courseFilePool = $this->config->lesson.'courseFile/'.$uid;
            $relationFilePool = $this->config->lesson.'relationFile/'.$uid;
            if(!is_dir($courseFilePool))
            {
                mkdir($courseFilePool,0755,true);
            }
            if(!is_dir($relationFilePool))
            {
                mkdir($relationFilePool,0755,true);
            }
            if(!empty($courseFile))
            {
                $courseFileInfos = Userfile::find(array("id in(".join(',',$courseFile).")"));
                foreach($courseFileInfos as $courseFileInfo)
                {
                    //判断分享池目录是否存在
                    $ext = strrchr($courseFileInfo->file_name, '.');
                    $file_name = $courseFilePool.'/'.$courseFileInfo->id.$ext;
                    if(!file_exists($file_name))
                    {
                        symlink(readlink($this->config->upload_path.$uid.$courseFileInfo->path.$courseFileInfo->file_name),$file_name);
                    }
                }
            }
            if(!empty($relationFile))
            {
                $relationFileInfos = Userfile::find(array("id in(".join(',',$relationFile).")"));
                foreach($relationFileInfos as $relationFileInfo)
                {
                    //判断分享池目录是否存在
                    $ext = strrchr($relationFileInfo->file_name, '.');
                    $file_name = $relationFilePool.'/'.$relationFileInfo->id.$ext;
                    if(!file_exists($file_name))
                    {
                        symlink(readlink($this->config->upload_path.$uid.$relationFileInfo->path.$relationFileInfo->file_name),$file_name);
                    }
                }
            }
        }while(false);
        $this->output();
    }

    /**
     * @brief 获取课程章节
     * @param lesson_id 课程ID
     */
    public function getLessonListAction()
    {
//        $this->params = array('lesson_id'=>1);
        do {
            $this->validation->add('lesson_id', new PresenceOf(array('message'=>'参数缺失:lesson_id')));
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
            $lesson_id = (int)$this->params['lesson_id'];
            $lesson = Lesson::findFirst(array("uid=$uid and id=$lesson_id"));
            if(!$lesson)
            {
                $this->responseData = array("code"=>\Code::ERROR_PARAMS,"msg"=>'课程不存在',"line"=>__LINE__,"data"=>array());
                break;
            }
            $lesson_lists = Lessonlist::find(array("lesson_id=$lesson_id","order"=>"sort asc"));
            $lesson_list_array = array();

            foreach($lesson_lists as $lesson_list)
            {
                if($lesson_list->path=='/')
                {
                    $lesson_list_array[$lesson_list->path.$lesson_list->name.'/']['lesson'] = $lesson_list->toArray();
                }
                else
                {
                    $lesson_list_array[$lesson_list->path]['child_list'][] = $lesson_list->toArray();
                }
            }
            $lesson_list_array = $this->arraySort($lesson_list_array,'sort');
            $this->responseData['data']['lesson_list'] = $lesson_list_array;
        }while(false);
        $this->output();
    }

    /**
     * @brief 课程评价列表
     * @param lesson_id 课程ID
     */
    public function lessonCommentListAction()
    {
//        $this->params = array('lesson_id'=>72);
        do {
            $this->validation->add('lesson_id', new PresenceOf(array('message'=>'参数缺失:lesson_id')));
            $messages = $this->validation->validate($this->params);
            if (count($messages)) {
                foreach ($messages as $message) {
                    array_push($this->errors, strval($message));
                }
                $this->responseData = array("code"=>\Code::ERROR_PARAMS,"msg"=>join(';', $this->errors),"line"=>__LINE__,"data"=>array());
                break;
            }
            $lesson_id = (int)$this->params['lesson_id'];
            $lesson = Lesson::findFirst(array("id=$lesson_id and status=1"));
            if(!$lesson)
            {
                $this->responseData = array("code"=>\Code::ERROR_PARAMS,"msg"=>'课程可能已经被删除',"line"=>__LINE__,"data"=>array());
                break;
            }
            $lessonComments = Lessoncomment::find(array("lesson_id=$lesson_id"));
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
            $this->responseData['data']['comment_list'] = array_values($lessonInfos);
        }while(false);
        $this->output();
    }

    /**
     * @brief 评价课程
     * @param lesson_id 课程ID
     * @param ref_uid 回复某个人的ID,非回复则为0
     * @param ref_id 回复的评论ID(请注意与lesson_id的区别,每个课程有个ID,每个评论也有个ID,这个是评论的ID),非回复则为0
     * @param content 评论的内容
     */
    public function commentLessonAction()
    {
//        $this->params = array('lesson_id'=>1,'ref_uid'=>0,'ref_id'=>0,'content'=>'这个课程好好\(^o^)/~');
        do {
            $this->validation->add('lesson_id', new PresenceOf(array('message'=>'参数缺失:lesson_id')));
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
            $lesson_id = (int)$this->params['lesson_id'];
            $ref_uid = (int)$this->params['ref_uid'];
            $ref_id = (int)$this->params['ref_id'];
            $content = $this->params['content'];
            $lesson = Lesson::findFirst(array("id=$lesson_id and status=1"));
            if(!$lesson)
            {
                $this->responseData = array("code"=>\Code::ERROR_PARAMS,"msg"=>'课程可能已经被删除',"line"=>__LINE__,"data"=>array());
                break;
            }
            $lessonComment = new Lessoncomment();
            $lessonComment->uid = $uid;
            $lessonComment->lesson_id = $lesson_id;
            $lessonComment->content = $content;
            $lessonComment->ref_id = $ref_id;
            $lessonComment->ref_uid = $ref_uid;
            $lessonComment->create_time = $lessonComment->setCreate_time();
            if(!$lessonComment->create())
            {
                $this->responseData = array("code"=>\Code::ERROR_DB,"msg"=>'课程评价失败',"line"=>__LINE__,"data"=>array());
                break;
            }
            $lessonComment = Lessoncomment::findFirst(array("id=".$lessonComment->id));
            $this->responseData['data']['content'] = $lessonComment->toArray();
            $this->responseData['data']['userInfo'] = $lessonComment->getUserinfo();
        }while(false);
        $this->output();
    }

    /**
     * @brief 课程详情
     * @param lesson_id 课程ID
     */
    public function getLessonDetailAction()
    {
//        $this->params = array('lesson_id'=>72);
        do {
            $this->validation->add('lesson_id', new PresenceOf(array('message'=>'参数缺失:lesson_id')));
            $messages = $this->validation->validate($this->params);
            if (count($messages)) {
                foreach ($messages as $message) {
                    array_push($this->errors, strval($message));
                }
                $this->responseData = array("code"=>\Code::ERROR_PARAMS,"msg"=>join(';', $this->errors),"line"=>__LINE__,"data"=>array());
                break;
            }
            /*if(!$this->checkUserLogin())
            {
                break;
            }*/
            $uid = $this->uid;
            $lesson_id = (int)$this->params['lesson_id'];
            $lesson = Lesson::findFirst(array("id=$lesson_id and status=1"));
            if(!$lesson)
            {
                $this->responseData = array("code"=>\Code::ERROR_PARAMS,"msg"=>'课程不存在',"line"=>__LINE__,"data"=>array());
                break;
            }
            //课程信息
            $this->responseData['data']['lesson'] = $lesson->toArray();
            $this->responseData['data']['user_info'] = $lesson->getUserinfo();
            //课程目录
            $lesson_lists = Lessonlist::find(array("lesson_id=$lesson_id","order"=>"sort asc"));
            $lesson_list_array = array();
            $lesson_files = array();
            foreach($lesson_lists as $lesson_list)
            {
                if($lesson_list->path=='/')
                {
                    $lesson_list_array[$lesson_list->path.$lesson_list->name.'/']['lesson'] = $lesson_list->toArray();
                }
                else
                {
                    //获取所有不重复的关联资料
                    if(!empty($lesson_list->file_ids))
                    {
                        $file_id_arr = explode(',',$lesson_list->file_ids);
                        foreach($file_id_arr as $file_id)
                        {
                            if(!in_array($file_id,$lesson_files))
                            {
                                array_push($lesson_files,$file_id);
                            }
                        }
                    }
                    $lesson_list_array[$lesson_list->path]['child_list'][] = $lesson_list->toArray();
                }
            }
            $lesson_list_array = $this->arraySort($lesson_list_array,'sort');
            $this->responseData['data']['lesson_list'] = $lesson_list_array;
            //课程评价
            $lessonComments = Lessoncomment::find(array("lesson_id=$lesson_id"));
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

            //课程相关资料
            $user_files = array();
            if(!empty($lesson_files))
            {
                $user_files = Userfile::find(array("id in (".join(',',$lesson_files).")"));
                $user_files = $user_files->toArray();
            }
            $this->responseData['data']['relation_file_list'] = $user_files;
            //学习人数
            $sql = "select count(DISTINCT uid) as c from edu_lesson_study where lesson_id=$lesson_id";
            $lessonStudyCounter = new Lessonstudy();
            $lessonStudyCounter = new Resultset(null, $lessonStudyCounter, $lessonStudyCounter->getReadConnection()->query($sql));
            $lessonStudyCounter = $lessonStudyCounter->toArray();
            //学习人数
            $this->responseData['data']['study_count'] = $lessonStudyCounter[0]['c'];

            //课程目录
            $lesson_lists = Lessonlist::find(array("lesson_id=".$lesson_id,"order"=>"sort asc"));
            //是否第一次该课程
            $lessonStudy = Lessonstudy::findFirst(array("uid=$uid and lesson_id=$lesson_id","order"=>"lesson_list_id desc"));
            if(!empty($lessonStudy))
            {
                if($lessonStudy->study_status==1)
                {
                    $lesson_list_array = array();
                    foreach($lesson_lists as $lesson_list)
                    {
                        if($lesson_list->path=='/')
                        {
                            $lesson_list_array[$lesson_list->path.$lesson_list->name.'/']['lesson'] = $lesson_list->toArray();
                        }
                        else
                        {
                            $lesson_list_array[$lesson_list->path]['child_list'][] = $lesson_list->toArray();
                        }
                    }
                    $lesson_list_array = $this->arraySort($lesson_list_array,'sort');
                    //课程章节ID列表
                    $lesson_list_ids = array();
                    foreach($lesson_list_array as $list)
                    {
                        foreach($list['child_list'] as $k=>$v)
                        {
                            array_push($lesson_list_ids,$v['id']);
                        }
                    }
                    //当前课程序号
                    $lesson_list_index = array_search($lessonStudy->lesson_list_id, $lesson_list_ids);
                    if(isset($lesson_list_ids[$lesson_list_index+1]))
                    {
                        $this->responseData['data']['study_status'] = 1;
                        $this->responseData['data']['study_last'] = $lesson_list_ids[$lesson_list_index+1];
                    }
                    else
                    {
                        $this->responseData['data']['study_status'] = 2;
                        $this->responseData['data']['study_last'] = $lessonStudy->lesson_list_id;
                    }
                }
                else
                {
                    $this->responseData['data']['study_status'] = 1;
                    $this->responseData['data']['study_last'] = $lessonStudy->lesson_list_id;
                }
            }
            else
            {
                $this->responseData['data']['study_status'] = 0;
                $this->responseData['data']['study_last'] = $lesson_lists[0]->id;
            }
            //点赞数
            $this->responseData['data']['like_count'] = 0;
        }while(false);
        $this->output();
    }

    /**
     * @brief 课程节点详情
     * @param lesson_list_id 课程ID
     */
    public function getLessonListDetailAction()
    {
//        $this->params = array('lesson_list_id'=>188);
        do {
            $this->validation->add('lesson_list_id', new PresenceOf(array('message'=>'参数缺失:lesson_list_id')));
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
            $lesson_list_id = (int)$this->params['lesson_list_id'];
            $lessonList = Lessonlist::findFirst(array("id=$lesson_list_id"));
            if(!$lessonList)
            {
                $this->responseData = array("code"=>\Code::ERROR_PARAMS,"msg"=>'课程节点不存在',"line"=>__LINE__,"data"=>array());
                break;
            }
            $lesson_id = $lessonList->lesson_id;
            $lesson = Lesson::findFirst(array("id=$lesson_id"));

            if(!$lesson)
            {
                $this->responseData = array("code"=>\Code::ERROR_PARAMS,"msg"=>'课程不存在',"line"=>__LINE__,"data"=>array());
                break;
            }
            $canLearn = 0;
            if($lesson->price>0)  //付费课程
            {
                $lessonPay = Lessonpay::findFirst(array("uid=$uid and lesson_id=$lesson_id"));
                if(!empty($lessonPay))
                {
                    $canLearn = 1;
                }
            }
            else
            {
                $canLearn = 1;
            }
            if($canLearn==0)
            {
                $this->responseData = array("code"=>\Code::ERROR_PARAMS,"msg"=>'课程需付费后观看',"line"=>__LINE__,"data"=>array());
                break;
            }
            //课程信息
            $this->responseData['data']['lesson'] = $lesson->toArray();
            //课程目录
            $lesson_lists = Lessonlist::find(array("lesson_id=$lesson_id","order"=>"sort asc"));
            $lesson_list_array = array();
            foreach($lesson_lists as $lesson_list)
            {
                if($lesson_list->path=='/')
                {
                    $lesson_list_array[$lesson_list->path.$lesson_list->name.'/']['lesson'] = $lesson_list->toArray();
                }
                else
                {
                    $lesson_list_array[$lesson_list->path]['child_list'][] = $lesson_list->toArray();
                }
            }
            $lesson_list_array = $this->arraySort($lesson_list_array,'sort');
            $this->responseData['data']['lesson_list'] = $lesson_list_array;

            //下节课程
            $next_lesson_tmp = 0;
            $next_lesson_id = 0;
            foreach($lesson_list_array as $list)
            {
                if($next_lesson_tmp == 9999)
                {
                    break;
                }
                foreach($list['child_list'] as $k=>$v)
                {
                    if($next_lesson_tmp==1)
                    {
                        $next_lesson_id = $v['id'];
                        $next_lesson_tmp = 9999;
                        break;
                    }
                    if($lesson_list_id==$v['id'])
                    {
                        $next_lesson_tmp = 1;
                    }
                }
            }
            $this->responseData['data']['next_lesson_id'] = $next_lesson_id;

            //课程评价
            $lessonComments = Lessoncomment::find(array("lesson_id=$lesson_id"));
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

            //课程相关资料
            $lesson_files = array();
            if(!empty($lessonList->file_ids))
            {
                $file_id_arr = explode(',',$lessonList->file_ids);
                foreach($file_id_arr as $file_id)
                {
                    if(!in_array($file_id,$lesson_files))
                    {
                        array_push($lesson_files,$file_id);
                    }
                }
            }
            $user_files = array();
            if(!empty($lesson_files))
            {
                $user_files = Userfile::find(array("id in (".join(',',$lesson_files).")"));
                $user_files = $user_files->toArray();
            }
            $this->responseData['data']['relation_file_list'] = $user_files;
            //学习人数
            $sql = "select count(DISTINCT uid) as c from edu_lesson_study where lesson_id=$lesson_id";
            $lessonStudyCounter = new Lessonstudy();
            $lessonStudyCounter = new Resultset(null, $lessonStudyCounter, $lessonStudyCounter->getReadConnection()->query($sql));
            $lessonStudyCounter = $lessonStudyCounter->toArray();
            $this->responseData['data']['study_count'] = $lessonStudyCounter[0]['c'];

            //最新学习人数列表
            $lessonStudyList = Lessonstudy::find(array("lesson_list_id=$lesson_list_id","order"=>"addtime desc",'limit'=>6));
            $studyUser = array();
            foreach($lessonStudyList as $lessonStudy)
            {
                $studyUser[$lessonStudy->uid] = $lessonStudy->getUserinfo();
            }
            $this->responseData['data']['study_users'] = array_values($studyUser);

            //章节提问
            $lessonAsk = Lessonask::find(array("lesson_list_id=$lesson_list_id and del=0","order"=>"create_time desc"));
            $this->responseData['data']['ask_list'] = $lessonAsk->toArray();
            //我的章节笔记
            $lessonNote = Lessonnote::find(array("lesson_id=$lesson_list_id and uid=$uid"));
            $this->responseData['data']['note_list'] = $lessonNote->toArray();
        }while(false);
        $this->output();
    }

    /**
     * @brief 课程提问
     * @param lesson_list_id 课程章节ID
     * @param time_point 提问时间点
     * @param content 提问内容
     * @param ref_uid 回答某个人的ID,非回复则为0
     * @param ref_id 回答的提问ID, 非回复则为0
     */
    public function askAction()
    {
//        $this->params = array('lesson_id'=>1);
        do {
            $this->validation->add('lesson_list_id', new PresenceOf(array('message'=>'参数缺失:lesson_list_id')));
            $this->validation->add('time_point', new PresenceOf(array('message'=>'参数缺失:time_point')));
            $this->validation->add('content', new PresenceOf(array('message'=>'参数缺失:content')));
            $this->validation->add('ref_uid', new PresenceOf(array('message'=>'参数缺失:ref_uid')));
            $this->validation->add('ref_id', new PresenceOf(array('message'=>'参数缺失:ref_id')));
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
            $lesson_list_id = (int)$this->params['lesson_list_id'];
            $time_point = $this->params['time_point'];
            $content = $this->params['content'];
            $ref_uid = (int)$this->params['ref_uid'];
            $ref_id = (int)$this->params['ref_id'];
            $lessonList = Lessonlist::findFirst(array("id=$lesson_list_id"));
            if(!$lessonList)
            {
                $this->responseData = array("code"=>\Code::ERROR_PARAMS,"msg"=>'课程章节不存在',"line"=>__LINE__,"data"=>array());
                break;
            }
            $lessonAsk = new Lessonask();
            $lessonAsk->lesson_id = $lessonList->lesson_id;
            $lessonAsk->lesson_list_id = $lesson_list_id;
            $lessonAsk->uid = $uid;
            $lessonAsk->time_point = $time_point;
            $lessonAsk->content = $content;
            $lessonAsk->ref_id = $ref_id;
            $lessonAsk->ref_uid = $ref_uid;
            if(!$lessonAsk->create())
            {
                $this->responseData = array("code"=>\Code::ERROR_PARAMS,"msg"=>'系统异常，提问失败',"line"=>__LINE__,"data"=>array());
                break;
            }
            $lessonAsk = Lessonask::findFirst(array("id=".$lessonAsk->id));
            $this->responseData['data']['content'] = $lessonAsk->toArray();
            $this->responseData['data']['userInfo'] = $lessonAsk->getUserinfo();
        }while(false);
        $this->output();
    }

    /**
     * @brief 回复课程提问
     * @param content 提问内容
     * @param ref_uid 回答某个人的ID,非回复则为0
     * @param ref_id 回答的提问ID, 非回复则为0
     */
    public function refAskAction()
    {
//        $this->params = array('lesson_id'=>1);
        do {
            $this->validation->add('content', new PresenceOf(array('message'=>'参数缺失:content')));
            $this->validation->add('ref_uid', new PresenceOf(array('message'=>'参数缺失:ref_uid')));
            $this->validation->add('ref_id', new PresenceOf(array('message'=>'参数缺失:ref_id')));
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
            $content = $this->params['content'];
            $ref_uid = (int)$this->params['ref_uid'];
            $ref_id = (int)$this->params['ref_id'];

            $ask = Lessonask::findFirst(array("id=$ref_id"));
            if(!$ask)
            {
                $this->responseData = array("code"=>\Code::ERROR_PARAMS,"msg"=>'提问不存在',"line"=>__LINE__,"data"=>array());
                break;
            }
            $lessonAsk = new Lessonask();
            $lessonAsk->lesson_id = $ask->lesson_id;
            $lessonAsk->lesson_list_id = $ask->lesson_list_id;
            $lessonAsk->uid = $uid;
            $lessonAsk->time_point = $ask->time_point;
            $lessonAsk->content = $content;
            $lessonAsk->ref_id = $ref_id;
            $lessonAsk->ref_uid = $ref_uid;
            if(!$lessonAsk->create())
            {
                $this->responseData = array("code"=>\Code::ERROR_PARAMS,"msg"=>'系统异常，回复失败',"line"=>__LINE__,"data"=>array());
                break;
            }
            $lessonAsk = Lessonask::findFirst(array("id=".$lessonAsk->id));
            $this->responseData['data']['content'] = $lessonAsk->toArray();
            $this->responseData['data']['userInfo'] = $lessonAsk->getUserinfo();
        }while(false);
        $this->output();
    }

    /**
     * @brief 课程提问列表
     * @param lesson_list_id 课程章节ID
     */
    public function lessonAskListAction()
    {
//        $this->params = array('lesson_list_id'=>191);
        do {
            $this->validation->add('lesson_list_id', new PresenceOf(array('message'=>'参数缺失:lesson_list_id')));
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
            $lesson_list_id = (int)$this->params['lesson_list_id'];
            $lessonList = Lessonlist::findFirst(array("id=$lesson_list_id"));
            if(!$lessonList)
            {
                $this->responseData = array("code"=>\Code::ERROR_PARAMS,"msg"=>'课程章节不存在',"line"=>__LINE__,"data"=>array());
                break;
            }
            //章节提问
            $lessonAsks = Lessonask::find(array("lesson_list_id=$lesson_list_id and del=0","order"=>"create_time asc"));
            $asks = array();
            foreach($lessonAsks as $k=>$lessonAsk)
            {
                $asks[$k] = $lessonAsk->toArray();
                $asks[$k]['lesson_list_info'] = $lessonAsk->getLessonlist();
                $asks[$k]['user_info'] = $lessonAsk->getUserinfo();
            }
            $this->responseData['data']['ask_list'] = array_values($asks);
        }while(false);
        $this->output();
    }

    /**
     * @brief 课程笔记
     * @param lesson_list_id 课程章节ID
     * @param time_point 提问时间点
     * @param content 提问内容
     * @param pic 图片 (可以不填)
     */
    public function noteAction()
    {
//        $this->params = array('lesson_id'=>1);
        do {
            $this->validation->add('lesson_list_id', new PresenceOf(array('message'=>'参数缺失:lesson_list_id')));
            $this->validation->add('time_point', new PresenceOf(array('message'=>'参数缺失:time_point')));
            $this->validation->add('time_point', new PresenceOf(array('message'=>'参数缺失:time_point')));
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
            $lesson_list_id = (int)$this->params['lesson_list_id'];
            $time_point = $this->params['time_point'];
            $content = $this->params['content'];
            $pic = isset($this->params['pic'])?$this->params['pic']:null;

            $lessonList = Lessonlist::findFirst(array("id=$lesson_list_id"));
            if(!$lessonList)
            {
                $this->responseData = array("code"=>\Code::ERROR_PARAMS,"msg"=>'课程章节不存在',"line"=>__LINE__,"data"=>array());
                break;
            }
            $lessonNote = new Lessonnote();
            $lessonNote->lesson_id = $lessonList->lesson_id;
            $lessonNote->lesson_list_id = $lesson_list_id;
            $lessonNote->uid = $uid;
            $lessonNote->time_point = $time_point;
            $lessonNote->content = $content;
            if(!empty($pic))
            {
                $lessonNote->pic = $pic;
            }
            if(!$lessonNote->create())
            {
                $this->responseData = array("code"=>\Code::ERROR_PARAMS,"msg"=>'系统异常，笔记记录失败',"line"=>__LINE__,"data"=>array());
                break;
            }
            $lessonNote = Lessonnote::findFirst(array("id=".$lessonNote->id));
            $this->responseData['data']['content'] = $lessonNote->toArray();
            $this->responseData['data']['lesson_list_info'] = $lessonNote->getLessonlist();
        }while(false);
        $this->output();
    }

    /**
     * @brief 课程笔记列表
     * @param lesson_id 课程ID
     */
    public function lessonNoteListAction()
    {
//        $this->params = array('lesson_id'=>72);
        do {
            $this->validation->add('lesson_id', new PresenceOf(array('message'=>'参数缺失:lesson_id')));
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
            $lesson_id = (int)$this->params['lesson_id'];
            $lesson = Lesson::findFirst(array("id=$lesson_id"));
            if(!$lesson)
            {
                $this->responseData = array("code"=>\Code::ERROR_PARAMS,"msg"=>'课程不存在',"line"=>__LINE__,"data"=>array());
                break;
            }
            //我的章节笔记
            $lessonNotes = Lessonnote::find(array("lesson_id=$lesson_id and uid=$uid","order"=>"addtime asc"));
            $notes = array();
            foreach($lessonNotes as $k=>$lessonNote)
            {
                $notes[$k] = $lessonNote->toArray();
                $notes[$k]['lesson_list_info'] = $lessonNote->getLessonlist();
            }
            $this->responseData['data']['note_list'] = array_values($notes);
        }while(false);
        $this->output();
    }

    /**
     * @brief 发布/取消发布课程
     * @param lesson_id 课程ID
     * @param type 1：发布（默认） 2：取消发布
     */
    public function pushLessonAction()
    {
//        $this->params = array('lesson_id'=>1);
        do {
            $this->validation->add('lesson_id', new PresenceOf(array('message'=>'参数缺失:lesson_id')));
            $this->validation->add('type', new InclusionIn(array('message'=>"参数type值只能为:1/2",
                'domain'=>array('1', '2'),
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
            $lesson_id = (int)$this->params['lesson_id'];
            $type = (int)$this->params['type'];
            $lesson = Lesson::findFirst(array("uid=$uid and id=$lesson_id"));
            if(!$lesson)
            {
                $this->responseData = array("code"=>\Code::ERROR_PARAMS,"msg"=>'课程不存在',"line"=>__LINE__,"data"=>array());
                break;
            }
            if($type==1)
            {
                if($lesson->status==\LessonStatus::PUBING)
                {
                    $this->responseData = array("code"=>\Code::ERROR_PARAMS,"msg"=>'课程已发布',"line"=>__LINE__,"data"=>array());
                    break;
                }
                $lesson->status = \LessonStatus::PUBING;
                $lesson->push_time = time();
            }
            else
            {
                //判断是否有学生在学习该课程
                $studyCount = Lessonstudy::count(array("lesson_id=$lesson_id"));
                if($studyCount>0)
                {
                    $this->responseData = array("code"=>\Code::ERROR_PARAMS,"msg"=>'课程已有学员学习，取消发布失败',"line"=>__LINE__,"data"=>array());
                    break;
                }
                $lesson->status = \LessonStatus::NOPUB;
                $lesson->push_time = 0;
            }
            if(!$lesson->update())
            {
                $this->responseData = array("code"=>\Code::ERROR_PARAMS,"msg"=>'系统异常',"line"=>__LINE__,"data"=>array());
                break;
            }
        }while(false);
        $this->output();
    }

    /**
     * @brief 发布课程列表
     * @param page 当前页 默认是：1
     * @param subjectId 学科ID
     * @param type 课程状态 1:非连载课程 2：连载中 3：完结课程
     * @param (选填) keywords 关键词
     * @param sort 排序 默认 0、最新  1、最热
     */
    public function getLessonsAction()
    {
        do {
            $this->validation->add('page', new PresenceOf(array('message'=>'参数缺失:page')));
            $messages = $this->validation->validate($this->params);
            if (count($messages)) {
                foreach ($messages as $message) {
                    array_push($this->errors, strval($message));
                }
                $this->responseData = array("code"=>\Code::ERROR_PARAMS,"msg"=>join(';', $this->errors),"line"=>__LINE__,"data"=>array());
                break;
            }
            $page = (int)$this->params['page']>0?$this->params['page']:1;
            $subjectId = isset($this->params['subjectId'])&&(int)$this->params['subjectId']>0?$this->params['subjectId']:0;
            $type = isset($this->params['type'])&&(int)$this->params['type']>0?$this->params['type']:0;
            $sort = isset($this->params['sort'])&&(int)$this->params['sort']>0?$this->params['sort']:0;
            $keywords = isset($this->params['keywords'])?$this->params['keywords'] : '';
            $counter = 12;  //默认单次返回的数据数量
            $start = ($page-1)*$counter;
            $condition = '';
            if($subjectId>0)
            {
                $condition .= " and father_subject_id = $subjectId";
            }
            if($type>0)
            {
                $condition .= " and type = $type";
            }
            if($keywords)
            {
                $condition .= " and title like '%$keywords%'";
            }
            $order = "push_time desc";
            if($sort>0)
            {
                //最热
                $order = "study_count desc";
            }
            $lessons = Lesson::find(array("status=1 $condition","order"=>$order,'limit'=>$counter,'offset'=>$start));
            $lessonIds = array();
            $lesson_arr = array();
            $subjectIds = array();
            foreach($lessons as $lesson)
            {
                if(!in_array($lesson->subject_id,$subjectIds))
                {
                    array_push($subjectIds,$lesson->subject_id);
                }
                array_push($lessonIds,$lesson->id);


                $lessonLists = Lessonlist::find("lesson_id=$lesson->id and file>0");
                $lessonFile = array();
                foreach($lessonLists as $lessonList)
                {
                    array_push($lessonFile,$lessonList->file);
                }
                $min = '00';
                $sec = '00';
                if(!empty($lessonFile))
                {
                    $files = Userfile::find(array("id in (".join(',',$lessonFile).")"));
                    $fileArr = array();
                    foreach($files as $file)
                    {
                        $ext = strrchr($file->file_name, '.');
                        $fileUrl = $this->config->lesson.'courseFile/'.$file->uid.'/'.$file->id.$ext;
                        array_push($fileArr,$fileUrl);
                    }
                    $t = $this->timeLong($fileArr);
                    $min = (int)($t/60);
                    if($min<10){
                        $min = '0'.$min;
                    }
                    $sec = (int)$t%60;
                    if($sec<10){
                        $sec = '0'.$sec;
                    }
                }
                $lesson_arr[$lesson->id] = $lesson->toArray();
                $lesson_arr[$lesson->id]['timeLone'] = $min.":".$sec;
                $lesson_arr[$lesson->id]['userInfo'] = $lesson->getUserInfo();
                $lesson_arr[$lesson->id]['study_count'] = 0;
            }
            if(!empty($subjectIds))
            {
                $subjects = Subject::find(array('id in ('.join(',',$subjectIds).')'));
                $subjectArr = array();
                foreach($subjects as $subject)
                {
                    $subjectArr[$subject->id] = $subject->subject_name;
                }
                foreach($lessons as $lesson)
                {
                    $lesson_arr[$lesson->id]['subject_name'] = $subjectArr[$lesson->subject_id];
                }
            }
            if(!empty($lessonIds))
            {
                //课程学习人数统计
                $sql = "select lesson_id,count(DISTINCT uid) as c from edu_lesson_study where lesson_id in (".join(',',$lessonIds).")";
                $lessonStudy = new Lessonstudy();
                $lessonStudy = new Resultset(null, $lessonStudy, $lessonStudy->getReadConnection()->query($sql));
                foreach($lessonStudy as $study)
                {
                    if(!empty($study->lesson_id))
                    {
                        $lesson_arr[$study->lesson_id]['study_count'] += $study->c;
                    }
                }
            }
            $lessonCounter = Lesson::count(array("status=1 $condition"));
            $this->responseData['data']['lessonCounter'] = $lessonCounter;
            $this->responseData['data']['lessons'] = array_values($lesson_arr);
        }while(false);
        $this->output();
    }

    /**
     * @brief 参加学习
     * @param lesson_id 课程ID
     * @param lesson_list_id 课程章节ID (选填)  第一次参加学习不传
     */
    public function LearnAction()
    {
        do {
            $this->validation->add('lesson_id', new PresenceOf(array('message'=>'参数缺失:lesson_id')));
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
            $lesson_id = (int)$this->params['lesson_id'];
            $lesson_list_id = isset($this->params['lesson_list_id'])?(int)$this->params['lesson_list_id']:0;
            $lesson = Lesson::findFirst(array("id=$lesson_id"));
            if(!$lesson)
            {
                $this->responseData = array("code"=>\Code::ERROR_PARAMS,"msg"=>'课程不存在',"line"=>__LINE__,"data"=>array());
                break;
            }
            if($lesson->uid==$uid)
            {
                break;
            }
            //课程目录
            $lessonList = Lessonlist::find(array("lesson_id = $lesson_id","order"=>"sort asc"));
            $lesson_list_array = array();
            foreach($lessonList as $lesson_list)
            {
                if($lesson_list->path=='/')
                {
                    $lesson_list_array[$lesson_list->path.$lesson_list->name.'/']['lesson'] = $lesson_list->toArray();
                }
                else
                {
                    $lesson_list_array[$lesson_list->path]['child_list'][] = $lesson_list->toArray();
                }
            }
            $lesson_list_array = $this->arraySort($lesson_list_array,'sort');

            $lesson_list_ids = array();
            foreach($lesson_list_array as $list)
            {
                foreach($list['child_list'] as $v)
                {
                    array_push($lesson_list_ids,$v['id']);
                }
            }
            if($lesson_list_id>0)
            {
                $list = Lessonlist::findFirst(array("lesson_id=$lesson_id and id=$lesson_list_id"));
                if(!$list)
                {
                    $this->responseData = array("code"=>\Code::ERROR_PARAMS,"msg"=>'课程章节不存在',"line"=>__LINE__,"data"=>array());
                    break;
                }

                $key = array_search($lesson_list_id, $lesson_list_ids);
                if(isset($lesson_list_ids[$key-1]))
                {
                    $myLastStudy = Lessonstudy::findFirst(array("uid=$uid and lesson_id=$lesson_id and lesson_list_id=".$lesson_list_ids[$key-1]));
                    if(!$myLastStudy)
                    {
                        $this->responseData = array("code"=>\Code::ERROR_PARAMS,"msg"=>'请先学习完前面的章节',"line"=>__LINE__,"data"=>array());
                        break;
                    }
                }
            }
            else
            {
                $lesson_list_id = $lesson_list_ids[0];
            }
            $thisStudy = Lessonstudy::findFirst("uid=$uid and lesson_id=$lesson_id");
            if(!$thisStudy)
            {
                $lesson->study_count = $lesson->study_count+1;
                if(!$lesson->update())
                {
                    $this->responseData = array("code"=>\Code::ERROR_PARAMS,"msg"=>'参加学习失败',"line"=>__LINE__,"data"=>array());
                    break;
                }
            }
            $lessonStudy = new Lessonstudy();
            $lessonStudy->lesson_id = $lesson_id;
            $lessonStudy->uid = $uid;
            $lessonStudy->lesson_list_id = $lesson_list_id;
            if(!$lessonStudy->create())
            {
                $this->responseData = array("code"=>\Code::ERROR_PARAMS,"msg"=>'参加学习失败',"line"=>__LINE__,"data"=>array());
                break;
            }
            $this->responseData['data']['lessonListID'] = $lesson_list_id;
        }while(false);
        $this->output();
    }

    /**
     * @brief 根据课程章节获取课件地址
     * @param lesson_list_id 课程ID
     */
    public function getLessonFilePathAction()
    {
//        $this->params = array('lesson_list_id'=>186);
        do {
            $this->validation->add('lesson_list_id', new PresenceOf(array('message'=>'参数缺失:lesson_list_id')));
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
            $lesson_list_id = (int)$this->params['lesson_list_id'];
            if($lesson_list_id==0)
            {
                $this->responseData = array("code"=>\Code::ERROR_PARAMS,"msg"=>'已经是课程最后一节',"line"=>__LINE__,"data"=>array());
                break;
            }
            $lessonList = Lessonlist::findFirst(array("id=$lesson_list_id"));
            if(!$lessonList)
            {
                $this->responseData = array("code"=>\Code::ERROR_PARAMS,"msg"=>'课程章节不存在',"line"=>__LINE__,"data"=>array());
                break;
            }
            $lesson = Lesson::findFirst(array("status=1 and id=".$lessonList->lesson_id));
            if(!$lesson)
            {
                $this->responseData = array("code"=>\Code::ERROR_PARAMS,"msg"=>'课程不存在',"line"=>__LINE__,"data"=>array());
                break;
            }
            $fileInfo = Userfile::findFirst(array("id=".$lessonList->file));
            if(!$fileInfo)
            {
                $this->responseData = array("code"=>\Code::ERROR_PARAMS,"msg"=>'章节课件已删除',"line"=>__LINE__,"data"=>array());
                break;
            }

            //课程目录
            $lesson_lists = Lessonlist::find(array("lesson_id=".$lessonList->lesson_id,"order"=>"sort asc"));
            $lesson_list_array = array();
            foreach($lesson_lists as $lesson_list)
            {
                if($lesson_list->path=='/')
                {
                    $lesson_list_array[$lesson_list->path.$lesson_list->name.'/']['lesson'] = $lesson_list->toArray();
                }
                else
                {
                    $lesson_list_array[$lesson_list->path]['child_list'][] = $lesson_list->toArray();
                }
            }
            $lesson_list_array = $this->arraySort($lesson_list_array,'sort');

            //课程章节ID列表
            $lesson_list_ids = array();
            foreach($lesson_list_array as $list)
            {
                foreach($list['child_list'] as $k=>$v)
                {
                    array_push($lesson_list_ids,$v['id']);
                }
            }

            //当前课程序号
            $lesson_list_index = array_search($lesson_list_id, $lesson_list_ids);
            //课程上一节
            if(isset($lesson_list_ids[$lesson_list_index-1])&&$uid!=$lesson->uid)
            {
                $myStudy = Lessonstudy::findFirst(array("uid=$uid and lesson_list_id=".$lesson_list_ids[$lesson_list_index-1]." and study_status=1"));
                if(!$myStudy)
                {
                    $this->responseData = array("code"=>\Code::ERROR_PARAMS,"msg"=>'请学习完上一节课程',"line"=>__LINE__,"data"=>array());
                    break;
                }
            }
            $newStudy = Lessonstudy::findFirst(array("lesson_list_id=$lesson_list_id and uid=$uid"));
            if(!$newStudy&&in_array($lesson_list_id,$lesson_list_ids)&&$uid!=$lesson->uid)
            {
                $newStudy = new Lessonstudy();
                $newStudy->uid = $uid;
                $newStudy->lesson_id = $lessonList->lesson_id;
                $newStudy->lesson_list_id = $lesson_list_id;
                if(!$newStudy->create())
                {
                    $this->responseData = array("code"=>\Code::ERROR_PARAMS,"msg"=>'学习记录失败',"line"=>__LINE__,"data"=>array());
                    break;
                }
            }

            $this->responseData['data']['next_lesson_list_id'] = $lesson_list_ids[$lesson_list_index+1];
            //课件地址
            $ext = strrchr($fileInfo->file_name, '.');
            $this->responseData['data']['path'] = 'rtmp://'.$this->config->vs2_serv['host'].':'.$this->config->vs2_serv['port'].'/vod/'.str_replace(".","",$ext).':lesson/courseFile/'.$fileInfo->uid.'/'.$lessonList->file.$ext;

            //关联资料
            $lesson_files = array();
            if(!empty($lessonList->file_ids))
            {
                $file_id_arr = explode(',',$lessonList->file_ids);
                foreach($file_id_arr as $file_id)
                {
                    if(!in_array($file_id,$lesson_files))
                    {
                        array_push($lesson_files,$file_id);
                    }
                }
            }
            $user_files = array();
            if(!empty($lesson_files))
            {
                $userFiles = Userfile::find(array("id in (".join(',',$lesson_files).")"));
                foreach($userFiles as $key=>$userFile){
                    $user_files[$key] = $userFile->toArray();
                    $dataSuffix = strrchr($userFile->file_name, '.');
                    $type = 'other';
                    if($dataSuffix===".pptx" || $dataSuffix===".ppt"){
                        $type='ppt';
                    }
                    if($dataSuffix===".docx" || $dataSuffix===".doc"){
                        $type='word';
                    }
                    if($dataSuffix===".xlsx" || $dataSuffix===".xls"){
                        $type='xls';
                    }
                    if($dataSuffix===".txt"){
                        $type='txt';
                    }
                    if($dataSuffix===".pdf"){
                        $type='pdf';
                    }
                    if($dataSuffix===".rar"){
                        $type='rar';
                    }
                    if($dataSuffix===".zip"){
                        $type='zip';
                    }
                    $user_files[$key]['ext'] = $type;
                    $fileSize = $this->fileSizeConv($userFile->file_size);
                    $user_files[$key]['fileSize'] = $fileSize;
                }
            }
            $this->responseData['data']['relation_file_list'] = $user_files;

        }while(false);
        $this->output();
    }

    /**
     * @brief 课程习题
     * @param lesson_list_id 课程章节ID
     * @param （选填）index 课程习题序号 默认：0
     * @param （选填）type 习题调用方式 (默认：0 点击习题按钮 1、播放到最后)
     */
    public function lessonQuestionAction()
    {
//        $this->params = array('lesson_list_id'=>186);
        do {
            $this->validation->add('lesson_list_id', new PresenceOf(array('message' => '参数缺失:lesson_list_id')));
            $messages = $this->validation->validate($this->params);
            if (count($messages)) {
                foreach ($messages as $message) {
                    array_push($this->errors, strval($message));
                }
                $this->responseData = array("code" => \Code::ERROR_PARAMS, "msg" => join(';', $this->errors), "line" => __LINE__, "data" => array());
                break;
            }
            if (!$this->checkUserLogin()) {
                break;
            }
            $uid = $this->uid;
            $lesson_list_id = (int)$this->params['lesson_list_id'];
            $type = isset($this->params['type']) ? (int)$this->params['type'] : 0;
            $index = isset($this->params['index']) ? (int)$this->params['index'] : -1;
            $lessonList = Lessonlist::findFirst(array("id=$lesson_list_id"));
            if (!$lessonList) {
                $this->responseData = array("code" => \Code::ERROR_PARAMS, "msg" => '课程章节不存在', "line" => __LINE__, "data" => array());
                break;
            }
            if($type == 1)
            {
                //记录学习状态
                $lessonStudy = Lessonstudy::findFirst(array("lesson_list_id=$lesson_list_id and uid=$uid and study_status=0"));
                if($lessonStudy)
                {
                    $lessonStudy->study_status = 1;
                    if(!$lessonStudy->update())
                    {
                        $this->responseData = array("code" => \Code::ERROR_PARAMS, "msg" => '结束学习失败', "line" => __LINE__, "data" => array());
                        break;
                    }
                }
            }
            if(!empty($lessonList->question_ids))
            {
                //章节题目列表
                $questionList = explode(',',$lessonList->question_ids);
                $this->responseData['data']['questionCounter'] = count($questionList);

                $now_question_index = 0;
                if($index==-1)  //默认点击答题
                {
                    $questionId = 0;
                    $userQuestion = Userquestion::findFirst(array("lesson_list_id=$lesson_list_id and uid=$uid","order"=>"id desc"));
                    if($userQuestion)
                    {
                        //最新的答题的习题ID
                        $questionId = $userQuestion->question_id;
                    }
                    //如果没有答过题
                    if($questionId == 0)
                    {
                        $now_question_id = $questionList[0];
                    }
                    else
                    {
                        $key = array_search($questionId,$questionList);
                        if($key<(count($questionList)-1))
                        {
                            $now_question_id = $questionList[$key+1];
                            $now_question_index = $key+1;
                        }
                        else
                        {
                            $now_question_id = $questionList[0];
                        }
                    }
                }
                else
                {
                    $now_question_index = $index;
                    $now_question_id = $questionList[$now_question_index];
                }
                $questionBox = array();
                $question = Question::findFirst(array("id=$now_question_id"));
                $questionBox[$now_question_id] = $question->toArray();
                $questionItem = Questionitem::find(array("question_id =$now_question_id","order"=>"sort asc,id asc"));
                foreach($questionItem as $item)
                {
                    $questionBox[$now_question_id]['item'][] = $item->toArray();
                }
                $questionBox[$now_question_id]['index'] = $now_question_index;
                unset($questionBox[$now_question_id]['answer'],$questionBox[$now_question_id]['analysis'],$questionBox[$now_question_id]['knowledge_point']);
                $this->responseData['data']['question'] = array_values($questionBox);

                $myQuestion = Userquestion::findFirst(array("lesson_list_id=$lesson_list_id and uid=$uid and question_id=$now_question_id"));
                $answer = array();
                if($myQuestion)
                {
                    $answer['status'] = $myQuestion->answer==$question->answer?1:0;
                    $answer['myAnswer'] = $myQuestion->answer;
                    $answer['true_answer'] = $myQuestion->true_answer;
                    $answer['analysis'] = $question->analysis;
                    $answer['knowledge_point'] = $question->knowledge_point;
                }
                $this->responseData['data']['myAnswer'] = $answer;
            }
            else
            {
                $this->responseData = array("code"=>\Code::ERROR_PARAMS,"msg"=>'课程暂无习题',"line"=>__LINE__,"data"=>array());
                break;
            }
        }while(false);
        $this->output();
    }

    /**
     * @brief 提交课程习题答案
     * @param lesson_list_id 课程章节ID
     * @param question_id 习题ID
     * @param answer 答案
     */
    public function postAnswerAction()
    {
//        $this->params = array('question_id'=>191,'question_id'=>191);
        do {
            $this->validation->add('lesson_list_id', new PresenceOf(array('message' => '参数缺失:lesson_list_id')));
            $this->validation->add('question_id', new PresenceOf(array('message'=>'参数缺失:question_id')));
            $this->validation->add('answer', new PresenceOf(array('message'=>'参数缺失:answer')));
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
            $lesson_list_id = (int)$this->params['lesson_list_id'];
            $question_id = (int)$this->params['question_id'];
            $answer = $this->params['answer'];

            $lessonList = Lessonlist::findFirst("id=$lesson_list_id");
            if(!$lessonList)
            {
                $this->responseData = array("code"=>\Code::ERROR_DB,"msg"=>'课件已删除',"line"=>__LINE__,"data"=>array());
                break;
            }
            $questionIds = explode(',',$lessonList->question_ids);
            if(!in_array($question_id,$questionIds))
            {
                $this->responseData = array("code"=>\Code::ERROR_DB,"msg"=>'习题不存在',"line"=>__LINE__,"data"=>array());
                break;
            }
            $question = Question::findFirst(array("id=$question_id"));
            if(!$question)
            {
                $this->responseData = array("code"=>\Code::ERROR_DB,"msg"=>'习题已删除',"line"=>__LINE__,"data"=>array());
                break;
            }
            $myQuestion = Userquestion::findFirst(array("uid=$uid and lesson_list_id=$lesson_list_id and question_id=$question_id"));
            if(!$myQuestion)
            {
                $userQuestion = new Userquestion();
                $userQuestion->uid = $uid;
                $userQuestion->lesson_id = $lessonList->lesson_id;
                $userQuestion->lesson_list_id = $lessonList->id;
                $userQuestion->question_id = $question_id;
                $userQuestion->answer = $answer;
                $userQuestion->true_answer = $question->answer;
                if(!$userQuestion->create())
                {
                    $this->responseData = array("code"=>\Code::ERROR_DB,"msg"=>'答题失败',"line"=>__LINE__,"data"=>array());
                    break;
                }
                $question = $question->toArray();
                if($question['answer']==$answer)
                {
                    $question['status'] = 1;
                }
                else
                {
                    $question['status'] = 0;
                }
                $this->responseData['data']['question'] = $question;
            }
            else
            {
                $this->responseData = array("code"=>\Code::ERROR_DB,"msg"=>'改题已经回答过',"line"=>__LINE__,"data"=>array());
                break;
            }
        }while(false);
        $this->output();
    }

    /**
     * @brief 获取课程学习列表
     * @param page (选填) 当前页 默认是：1
     */
    public function getlessonStudyAction()
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

            $sql = "select * from edu_lesson_study where uid=$uid group by lesson_id order by addtime desc limit $offset,$limit";
            $lessonStudy = new Lessonstudy();
            $lessonStudys = new Resultset(null, $lessonStudy, $lessonStudy->getReadConnection()->query($sql));
            $lessonArr = array();
            foreach($lessonStudys as $k=>$lessonStudy)
            {
                $lesson = $lessonStudy->getLesson();
                $lessonArr[$k]['userinfo'] = $lesson->getUserinfo()->toArray();
                $lessonArr[$k]['lesson'] = $lesson->toArray();
                $lessonLists = Lessonlist::find("lesson_id=$lesson->id and file>0");
                $lessonFile = array();
                foreach($lessonLists as $lessonList)
                {
                    array_push($lessonFile,$lessonList->file);
                }
                $min = '00';
                $sec = '00';
                if(!empty($lessonFile))
                {
                    $files = Userfile::find(array("id in (".join(',',$lessonFile).")"));
                    $fileArr = array();
                    foreach($files as $file)
                    {
                        $ext = strrchr($file->file_name, '.');
                        $fileUrl = $this->config->lesson.'courseFile/'.$file->uid.'/'.$file->id.$ext;
                        array_push($fileArr,$fileUrl);
                    }
                    $t = $this->timeLong($fileArr);
                    $min = (int)($t/60);
                    if($min<10){
                        $min = '0'.$min;
                    }
                    $sec = (int)$t%60;
                    if($sec<10){
                        $sec = '0'.$sec;
                    }
                }
                $lessonArr[$k]['timeLone'] = $min.":".$sec;
            }
            $this->responseData['data']['lessons'] = $lessonArr;
            $sql = "select count(DISTINCT lesson_id) as c from edu_lesson_study where uid=$uid";
            $lessonStudy = new Lessonstudy();
            $lessonStudyCount = new Resultset(null, $lessonStudy, $lessonStudy->getReadConnection()->query($sql));
            $lessonStudyCount= $lessonStudyCount->toArray();
            $count = $lessonStudyCount[0]['c'];
            $this->responseData['data']['count'] = $count;
        }while(false);
        $this->output();
    }

    /**
     * @brief 课程管理提问列表
     * @param lesson_id 课程ID
     */
    public function lessonManagerAskListAction()
    {
        do {
            $this->validation->add('lesson_id', new PresenceOf(array('message'=>'参数缺失:lesson_id')));
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
            $lesson_id = (int)$this->params['lesson_id'];
            $lesson = Lesson::findFirst(array("id=$lesson_id"));
            if(!$lesson||$lesson->uid!=$uid)
            {
                $this->responseData = array("code"=>\Code::ERROR_DB,"msg"=>'课程已删除或不存在',"line"=>__LINE__,"data"=>array());
                break;
            }
            //章节提问
            $lessonAsks = Lessonask::find(array("lesson_id=$lesson_id and del=0","order"=>"create_time asc"));
            $asks = array();
            foreach($lessonAsks as $lessonAsk){
                $askInfo = $lessonAsk->toArray();
                $asks[$lessonAsk->id]['lesson_list_info'] = $lessonAsk->getLessonlist();
                if( $lessonAsk->ref_uid == 0 ){
                    $askInfo['refUserName'] = "";
                    $askInfo['refHeadpic'] = array();
                }
                else
                {
                    $refUserInfo = $lessonAsk->getRefUserInfo();
                    $askInfo['refUserName'] = $refUserInfo->nick_name;
                    $askInfo['refHeadpic'] = $refUserInfo->headpic;
                }
                $userInfo = $lessonAsk->getUserinfo();
                $askInfo['userName'] = $userInfo->nick_name;
                $askInfo['headpic'] = $userInfo->headpic;
                if($lessonAsk->ref_id == 0 ){
                    $asks[$lessonAsk->id][] = $askInfo;
                }
                else
                {
                    $asks[$lessonAsk->ref_id][] = $askInfo;
                }
            }
            $asks[$lessonAsk->id]['lesson_list_info'] = $lessonAsk->getLessonlist();
            $this->responseData['data']['ask_list'] = array_values($asks);
        }while(false);
        $this->output();
    }

    /**
     * @brief 课程学员列表
     * @param lesson_id 课程ID
     */
    public function getlessonStudentListAction()
    {
        do {
            if(!$this->checkUserLogin())
            {
                break;
            }
            $this->validation->add('lesson_id', new PresenceOf(array('message'=>'参数缺失:lesson_id')));
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
            $lesson_id = (int)$this->params['lesson_id'];
            $lesson = Lesson::findFirst(array("id=$lesson_id"));
            if(!$lesson||$lesson->uid!=$uid)
            {
                $this->responseData = array("code"=>\Code::ERROR_DB,"msg"=>'课程已删除或不存在',"line"=>__LINE__,"data"=>array());
                break;
            }

            $lessonList = Lessonlist::find(array("lesson_id=$lesson_id and file>0"));
            $question_ids = array();
            $all = 0;
            $all_list = 0;
            foreach($lessonList as $question)
            {
                $all_list++;
                if(!empty($question->question_ids))
                {
                    $question_id_arr = explode(',',$question->question_ids);
                    foreach($question_id_arr as $question_id)
                    {
                        if(!in_array($question_id,$question_ids))
                        {
                            array_push($question_ids,$question_id);
                        }
                    }
                    $all += count($question_ids);
                }
            }

            $lessonStudys = Lessonstudy::find(array("lesson_id=$lesson_id and uid!=$uid","order"=>"addtime asc"));
            $userIdArr = array();
            $userArr = array();
            foreach($lessonStudys as $lessonStudy)
            {
                if(!in_array($lessonStudy->uid,$userIdArr))
                {
                    $userArr[$lessonStudy->uid]['userInfo'] = $lessonStudy->getUserInfo();
                    $userArr[$lessonStudy->uid]['start_time'] = $lessonStudy->addtime;
                    $userArr[$lessonStudy->uid]['answer_false']=0;
                    $userArr[$lessonStudy->uid]['answer_all']=0;
                    $userArr[$lessonStudy->uid]['all']=$all;
                    $userArr[$lessonStudy->uid]['note_count'] = Lessonnote::count(array("uid=".$lessonStudy->uid." and lesson_id=$lesson_id"));
                    $userArr[$lessonStudy->uid]['ask_count'] = Lessonask::count(array("uid=".$lessonStudy->uid." and lesson_id=$lesson_id"));
                    array_push($userIdArr,$lessonStudy->uid);
                }
                $userArr[$lessonStudy->uid]['node']++;
            }
            if(!empty($userIdArr))
            {
                foreach($userIdArr as $userId)
                {
                    $userArr[$userId]['process'] = (int)(100*$userArr[$userId]['node']/$all_list).'%';

                    $userQuerstions = Userquestion::find(array("uid=$userId and lesson_id=$lesson_id"));
                    foreach($userQuerstions as $userQuerstion)
                    {
                        $userArr[$userId]['answer_all']++;
                        if($userQuerstion->true_answer!=$userQuerstion->answer)
                        {
                            $userArr[$userQuerstion->uid]['answer_false']++;
                        }
                    }
                }
            }
            $this->responseData['data']['user_list'] = array_values($userArr);
        }while(false);
        $this->output();
    }
}