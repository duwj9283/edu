<?php
namespace Cloud\Frontend\Controllers;
use Cloud\Models\Lesson;
use Cloud\Models\Lessonlist;
use Cloud\Models\Lessonstudy;
use Cloud\Models\Subject;
use Cloud\Models\Userfile;
use Cloud\Models\Userinfo;
use Cloud\Models\Lessonnote;
use Cloud\Models\Lessonask;
use Cloud\Models\Siteconfig;
use Cloud\Models\Lessoncomment;
use Phalcon\Mvc\Model\Resultset\Simple as Resultset;

class CourseController extends ControllerBase
{
    public function indexAction()
    {
        $subjects = Subject::find(array("father_id=0 and visible=1","order"=>"id asc"));
        $subjectArr = array();
        foreach($subjects as $k=>$subject)
        {
            $subjectArr[$k] = $subject->toArray();
            $lessonCount = lesson::count(array("father_subject_id=".$subject->id));
            $subjectArr[$k]['lessonCount'] = $lessonCount;
        }
        $this->view->subjects = $subjectArr;

        //幻灯片
        $siteConfig = Siteconfig::findFirst(array("option_title='site_banner3'"));
        $this->view->images = explode('|',$siteConfig->option_value);
    }
    public function createAction()
    {
        if($this->bSignedIn){

        }else{
            $this->response->redirect('/index');
        }
    }
    public function updateAction($lesson_id=0)
    {
        if($this->bSignedIn){
            $uid= $this->user->uid;
            $lesson = Lesson::findFirst(array("uid=$uid and id=$lesson_id "));
            if($lesson)
            {
                $lesson=$lesson->toArray();
                $lesson['label']=explode(',',$lesson['label']);
                $this->view->lesson = $lesson;
                $lessonLists = lessonlist::find(array("lesson_id=$lesson_id","order"=>"sort asc"));
                $lesson_list_array = array();
                foreach($lessonLists as $lesson_list)
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
                $lesson_list_array = $this->array_sort($lesson_list_array,'sort');
                $this->view->lessonList = $lesson_list_array;
                $subject = Subject::findFirst(array("id=".$lesson['subject_id']));
                $this->view->subject_father_id = $subject->father_id;
            }else{
                $this->response->redirect('/index');
            }
        }else{
            $this->response->redirect('/index');
        }
    }
    /*课程管理*/
    public function manageAction(){
        do{
            if($this->bSignedIn){

            }else{
                $this->response->redirect('/index');
            }
        }while(false);
    }
    /*课程学习*/
    public function studyAction()
    {
        do{
            if($this->bSignedIn){

            }else{
                $this->response->redirect('/index');
            }
        }while(false);
    }
    /*题库*/
    public function examAction()
    {
        do{
            if($this->bSignedIn){

            }else{
                $this->response->redirect('/index');
            }
        }while(false);
    }
    /*课程详情*/
    public function detailAction($lesson_id){
        do{
            $uid = 0;
            if($this->bSignedIn)
            {
                $uid= $this->user->uid;
            }

            $lesson = Lesson::findFirst(array("id=$lesson_id"));//当前课程详情
            if($lesson->uid!=$uid)
            {
                $lesson =  Lesson::findFirst(array("id=$lesson_id and status=1"));
            }
            $lesson->label=$lesson->label?explode(',',$lesson->label):'';
            $userInfo= $lesson->getUserinfo();//讲师
            $lesson_lists = Lessonlist::find(array("lesson_id=$lesson_id","order"=>"sort asc"));//课程目录

            //课程时长统计
            $lessonFile = array();
            foreach($lesson_lists as $lessonList)
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
                    $fileUrl = $this->config->upload_path.'lesson/courseFile/'.$file->uid.'/'.$file->id.$ext;
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
            $timeLong = $min.":".$sec;

            //关联资料
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
                    $lesson_list_array[$lesson_list->path]['child_list'][] = $lesson_list->toArray();
                }
            }
            $lesson_list_array = $this->arraySort($lesson_list_array,'sort');
            $i = $j = 0;
            foreach($lesson_list_array as $v)
            {
                $i++;
                $j = 0;
                foreach($v['child_list'] as $key=>$val)
                {
                    $j++;
                    $lesson_files[$val['id']]['index'] = $i;
                    $lesson_files[$val['id']]['child_index'] = $j;
                    $lesson_files[$val['id']]['file_ids'] = $val['file_ids'];
                    $lesson_files[$val['id']]['father_name'] = substr($val['path'],1,strlen($val['path'])-2);
                    $lesson_files[$val['id']]['child_name'] = $val['name'];
                }
            }
            $user_files = array();
            if(!empty($lesson_files))
            {
                foreach($lesson_files as $k=>$v)
                {
                    $user_files[$k] = $v;
                    if(!empty($v['file_ids']))
                    {
                        $userFiles = Userfile::find(array("id in (".$v['file_ids'].")"));
                        foreach($userFiles as $key=>$userFile){
                            $user_files[$k]['fileInfo'][$key] = $userFile->toArray();
                            $dataSuffix = strrchr($userFile->file_name, '.');
                            $type = 'other';
                            if($dataSuffix===".pptx" || $dataSuffix===".ppt"){
                                $type='ppt';
                            }
                            if($dataSuffix===".docx" || $dataSuffix===".doc"){
                                $type='doc';
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
                            $user_files[$k]['fileInfo'][$key]['ext'] = $type;
                            $fileSize = $this->fileSizeConv($userFile->file_size);
                            $user_files[$k]['fileInfo'][$key]['fileSize'] = $fileSize;
                        }
                    }
                    else
                    {
                        $user_files[$k]['fileInfo'] = array();
                    }
                }
            }
            //学习人数
            $sql = "select count(DISTINCT uid) as c from edu_lesson_study where lesson_id=$lesson_id";
            $lessonStudyCounter = new Lessonstudy();
            $lessonStudyCounter = new Resultset(null, $lessonStudyCounter, $lessonStudyCounter->getReadConnection()->query($sql));
            $lessonStudyCounter = $lessonStudyCounter->toArray();
            //是否第一次该课程
            $lessonStudy = Lessonstudy::findFirst(array("uid=$uid and lesson_id=$lesson_id","order"=>"lesson_list_id desc"));
            if(!empty($lessonStudy))
            {
                if($lessonStudy->study_status==1)
                {
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
                        $study_status = 1;
                        $study_last = $lesson_list_ids[$lesson_list_index+1];
                    }
                    else
                    {
                        $study_status = 2;
                        $study_last = $lessonStudy->lesson_list_id;
                    }
                }
                else
                {
                    $study_status = 1;
                    $study_last= $lessonStudy->lesson_list_id;
                }
            }
            else
            {
                $study_status = 0;//未学习
                $study_last = $lesson_lists[0]->id;
            }
            $this->view->study_status = $study_status;
            $this->view->study_last = $study_last;
            $this->view->lesson = $lesson;
            $this->view->teacherInfo = $userInfo;
            $this->view->timeLone = $timeLong;
            $this->view->lessonLists = $lesson_list_array;//目录
            $this->view->relationFileList = array_values($user_files);//关联资料
            $this->view->lessonStudyCount = $lessonStudyCounter[0]['c'];
        }while(false);
    }
    /*课程播放*/
    public function playAction($lesson_list_id){
        do{
            if($this->bSignedIn){
                $uid= $this->user->uid;
                $lessonList = Lessonlist::findFirst(array("id=$lesson_list_id"));
                $lesson_id = $lessonList->lesson_id;
                $lesson = Lesson::findFirst(array("id=$lesson_id"));//课程信息
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

                //学习人数
                $sql = "select count(DISTINCT uid) as c from edu_lesson_study where lesson_id=$lesson_id";
                $lessonStudyCounter = new Lessonstudy();
                $lessonStudyCounter = new Resultset(null, $lessonStudyCounter, $lessonStudyCounter->getReadConnection()->query($sql));
                $lessonStudyCounter = $lessonStudyCounter->toArray();
                //最新学习人数列表
                $lessonStudyList = Lessonstudy::find(array("lesson_list_id=$lesson_list_id","order"=>"addtime desc",'limit'=>6));
                $studyUser = array();
                foreach($lessonStudyList as $lessonStudy)
                {
                    $studyUser[$lessonStudy->uid] = $lessonStudy->getUserinfo();
                }

                //章节提问
                $lessonAsk = Lessonask::find(array("lesson_list_id=$lesson_list_id and del=0","order"=>"create_time desc"));
                $asks = array();
                foreach($lessonAsk as $k=>$lessonAsk)
                {
                    if($lessonAsk->ref_id){
                        $asks[$lessonAsk->ref_id]['child'][$lessonAsk->id] = $lessonAsk->toArray();
                        $asks[$lessonAsk->ref_id]['child'][$lessonAsk->id]['user_info'] = $lessonAsk->getUserinfo()->toArray();
                    }else{
                        $asks[$lessonAsk->id]['ask'] = $lessonAsk->toArray();
                        $asks[$lessonAsk->id]['user_info'] = $lessonAsk->getUserinfo()->toArray();

                    }
                }
                $lessonNotes = Lessonnote::find(array("lesson_id=$lesson_id and uid=$uid","order"=>"addtime asc"));
                $notes = array();
                foreach($lessonNotes as $k=>$lessonNote)
                {
                    $notes[$k] = $lessonNote->toArray();
                    $notes[$k]['lesson_list_info'] = $lessonNote->getLessonlist()->toArray();
                }
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
                $this->view->comment_list =$lessonInfos;
                $this->view->note_list = $notes;
                $this->view->ask_list = $asks;
                $this->view->study_users = array_values($studyUser);
                $this->view->study_count = $lessonStudyCounter[0]['c'];
                $this->view->lesson_list = $lesson_list_array;
                $this->view->lesson = $lesson->toArray();
                $this->view->lesson_list_id = $lesson_list_id;

            }else{
                $this->response->redirect('login/login?backurl='.$_SERVER['REQUEST_URI']);
            }
        }while(false);
    }

    /*获取ueditor编辑器图片*/
    public function getUeditorImgAction($url1='upload',$url2='ueditor',$url3='image',$url4='',$url5='')
    {
        do{
            if($url4=='' or $url5==''){
                $this->responseData = array("code"=>\Code::ERROR,"msg"=>"图片不存在","line"=>__LINE__,"data"=>array());
                break;
            }
            $file = $this->config->upload_path.$url1.'/'.$url2.'/'.$url3.'/'.$url4.'/'.$url5;
            $size = filesize($file);
            $last_modified_time = filemtime($file);
            $etag = md5_file($file);
            $this->response->setHeader("Content-Type", "image/jpg");
            $this->response->setHeader("Content-Length", $size);
            $this->response->setHeader("Last-Modified", gmdate("D, d M Y H:i:s", $last_modified_time)." GMT");
            $this->response->setHeader("Etag:", $etag);
            if (@strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']) == $last_modified_time ||
                @trim($_SERVER['HTTP_IF_NONE_MATCH']) == $etag) {
                header("HTTP/1.1 304 Not Modified");
                exit;
            }
            $content = readfile($file);
            $this->response->setContent($content);
        }while(false);
    }

}