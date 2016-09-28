<?php
namespace Cloud\Frontend\Controllers;

use Cloud\Models\Lesson;
use Cloud\Models\Lessonstudy;
use Cloud\Models\Live;
use Cloud\Models\Mlesson;
use Cloud\Models\Mlessonstudy;
use Cloud\Models\Userdynamic;
use Cloud\Models\Userfile;
use Cloud\Models\Userinfo;
use Cloud\Models\User;
use Cloud\Models\Usernews;
use Cloud\Models\Subject;
use Cloud\Models\Userfollow;
use Cloud\Models\Messagestatus;
use Phalcon\Mvc\Model\Resultset\Simple as Resultset;

class UserController extends ControllerBase
{
    public function indexAction()
    {
		if($this->bSignedIn){
            $class=['一班','二班','三班','四班','五班','六班','七班','八班'];//班级
            /*查询学科名称*/
            $subjectId = $this->userInfo->subject;
            $subjectFatherName=$subjectName='';
            if(!empty($subjectId)){
                $subjectInfo = Subject::findFirst("id=$subjectId");
                $subjectFatherId = $subjectInfo->father_id;
                $subjectFatherInfo = Subject::findFirst("id=$subjectFatherId");
                $subjectFatherName = $subjectFatherInfo->subject_name;
                $subjectName = $subjectInfo->subject_name;
            }
            $this->view->subjectFatherId = $subjectFatherId;
            $this->view->subjectId = $subjectId;
            $this->view->subjectFatherName = $subjectFatherName;
            $this->view->subjectName = $subjectName;

            $this->view->classArr = $class;

        }
		else{
			$this->response->redirect("index");
		}

    }
    public function index1Action()
    {
        if($this->bSignedIn){
        }
        else{
            $this->response->redirect("index");
        }
    }
    /*我的分享*/
    public function shareAction()
    {
        if($this->bSignedIn){
        }
        else{
            $this->response->redirect("index");
        }
    }
    /*我的关注*/
    public function FollowAction()
    {
        if($this->bSignedIn){
        }
        else{
            $this->response->redirect("/index");
        }

    }
    /*文章列表*/
    public function newsAction()
    {
        if($this->bSignedIn){
            $uid = $this->userInfo->uid;
            $total = Usernews::count(array("uid=$uid"));
            $this->view->total = $total;
        }
        else{
            $this->response->redirect("index");
        }

    }
    /*文章详情*/
    public function newsInfoAction($id)
    {
        $userNews = Usernews::findFirst(array("id=$id"));
        $this->view->news = $userNews->toArray();
        $this->view->id = $id;
    }
    /*编辑文章*/
    public function editNewsAction($id)
    {
        if($this->bSignedIn){
            $uid = $this->userInfo->uid;
            $userNews = Usernews::findFirst(array("id=$id and uid=$uid"));
            $this->view->news = $userNews->toArray();
            $this->view->id = $id;
        }
        else{
            $this->response->redirect("index");
        }
    }
    public function addNewsAction($id)
    {
        if($this->bSignedIn){
            $this->view->id = $id;
        }
        else{
            $this->response->redirect("index");
        }
    }
    public function messageAction()
    {
        if($this->bSignedIn){
            $uid = $this->userInfo->uid;
            $total = Messagestatus::count(array("receiver_id=$uid and status=1"));
            $this->view->total = $total;
        }
        else{
            $this->response->redirect("index");
        }

    }

    /*修改密码*/
    public function modifypswAction()
    {
        if($this->bSignedIn){
        }
        else{
            $this->response->redirect("index");
        }
    }
    /*个人空间默认页面--个人动态*/
    public function personAction()
    {
        if($this->bSignedIn){
        }
        else{
            $this->response->redirect("index");
        }

    }
    /*个人空间--资源*/
    public function resourceAction()
    {
        if($this->bSignedIn){
        }
        else{
            $this->response->redirect("index");
        }
    }
    /*个人中心--直播*/
    public function liveAction()
    {
        if($this->bSignedIn){
        }
        else{
            $this->response->redirect("index");
        }
    }
    /*个人中心--微课*/
    public function micoAction()
    {
        if($this->bSignedIn){
        }
        else{
            $this->response->redirect("index");
        }
    }
    /*个人中心--课程*/
    public function courseAction()
    {
        if($this->bSignedIn){
        }
        else{
            $this->response->redirect("index");
        }
    }
    /*个人中心--活动*/
    public function activityAction()
    {
        if($this->bSignedIn){
        }
        else{
            $this->response->redirect("index");
        }
    }
    /*个人主页--动态*/
    public function zoneAction($uid)
    {
        $uid = isset($uid)&&(int)$uid>0?(int)$uid:0;
        $data = array();
        if($uid==0)
        {
            //进入自己的空间
            if($this->bSignedIn)
            {
                $uid = $this->userInfo->uid;
                $this->view->follow = 2;
            }
            else
            {
                $this->response->redirect("index");
                return;
            }
        }
        else
        {
            //已登录
            if($this->bSignedIn)
            {
                if($uid==$this->userInfo->uid)
                {
                    $this->view->follow = 2;
                }
                else
                {
                    $userInfo = Userinfo::findFirst(array("uid=$uid"));
                    $userInfo->visited_count = $userInfo->visited_count+1;
                    $userInfo->update();
                    $userFollow = Userfollow::findFirst(array("uid=".$this->userInfo->uid." and tuid=$uid","limit"=>1));
                    if($userFollow)
                    {
                        $this->view->follow = 1;
                    }
                    else
                    {
                        $this->view->follow = 0;
                    }
                }
            }
            else
            {
                $this->view->follow = 0;
            }
        }
        $userInfo = Userinfo::findFirst(array("uid=$uid"));
        $user = User::findFirst(array("uid=$uid"));
        $subject = Subject::findFirst(array("id=".$userInfo->subject));
        $userInfo->subject_name = $subject->subject_name;
        $this->view->UserInfo = $userInfo;
        $this->view->User = $user;

        //关注人数
        $userFollowCounter = Userfollow::count(array("tuid=$uid"));
        $this->view->userFollowCounter = $userFollowCounter;
        //资源
        $files = Userfile::find(array("visible=1 and uid=$uid and file_status=".\FileStatus::NORMAL,"limit"=>6,"order"=>"addtime desc"));
        $fileArr = array();
        foreach($files as $k=>$file)
        {
            $dataSuffix = strrchr($file->file_name, '.');
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

            $fileArr[$k] = $file->toArray();
            $fileArr[$k]['sizeConv'] = $this->fileSizeConv($file->file_size);
            $fileArr[$k]['ext'] =  $type;
        }
        $data['fileList'] = $fileArr;
        $data['fileCount'] = Userfile::count(array("visible=1 and uid=$uid and file_status=".\FileStatus::NORMAL));

        //课程
        $lessons = Lesson::find(array("uid=$uid","order"=>"addtime desc","limit"=>6));
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
            $lesson_arr[$lesson->id] = $lesson->toArray();
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
        if(!empty($lessonIds)) {
            //课程学习人数统计
            $sql = "select lesson_id,count(DISTINCT uid) as c from edu_lesson_study where lesson_id in (" . join(',', $lessonIds) . ")";
            $lessonStudy = new Lessonstudy();
            $lessonStudy = new Resultset(null, $lessonStudy, $lessonStudy->getReadConnection()->query($sql));
            foreach ($lessonStudy as $study) {
                if (!empty($study->lesson_id)) {
                    $lesson_arr[$study->lesson_id]['study_count'] += $study->c;
                }
            }
        }
        $data['lessonList'] = array_values($lesson_arr);
        $data['lessonCount'] = Lesson::count(array("uid=$uid"));

        //微课
        $mLessons = Mlesson::find(array("status=1 and uid=$uid","order"=>"addtime desc","limit"=>6));
        $mLessonArr = array();
        $subjectIds = array();
        foreach($mLessons as $mLesson)
        {
            if(!in_array($mLesson->subject_id,$subjectIds))
            {
                array_push($subjectIds,$mLesson->subject_id);
            }
            $studyCount = Mlessonstudy::count(array("m_lesson_id=".$mLesson->id));
            $mLessonArr[$mLesson->id] = $mLesson->toArray();
            $mLessonArr[$mLesson->id]['userInfo'] = $mLesson->getUserinfo()->toArray();
            $mLessonArr[$mLesson->id]['studyCount'] = $studyCount;
        }
        if(!empty($subjectIds))
        {
            $subjects = Subject::find(array('id in ('.join(',',$subjectIds).')'));
            $subjectArr = array();
            foreach($subjects as $subject)
            {
                $subjectArr[$subject->id] = $subject->subject_name;
            }
            foreach($mLessons as $mLesson)
            {
                $mLessonArr[$mLesson->id]['subject_name'] = $subjectArr[$mLesson->subject_id];
            }
        }
        $data['mlessonList'] = array_values($mLessonArr);
        $data['mlessonCount'] = Mlesson::count(array("status=1 and uid=$uid"));

        //直播
        $lives = Live::find(array("publish_type=1 and uid=$uid","order"=>"start_time asc",'limit'=>6));
        $liveArr = array();
        foreach ($lives as $live) {
            $liveArr[$live->id] = $live->toArray();
            if($live->start_time>date('Y-m-d H:i:s'))
            {
                $liveArr[$live->id]['live_status'] = 0;
            }
            else if($live->start_time<date('Y-m-d H:i:s')&&$live->end_time>date('Y-m-d H:i:s'))
            {
                $liveArr[$live->id]['live_status'] = 1;
            }
            else
            {
                $liveArr[$live->id]['live_status'] = 2;
            }
            $liveArr[$live->id]['userInfo'] = $live->getUserinfo()->toArray();
        }
        $data['liveList'] = array_values($liveArr);
        $data['liveCount'] = Live::count(array("publish_type=1 and uid=$uid"));
        //文章
        $userNews = Usernews::find(array("uid=$uid and status=0","limit"=>2,"order"=>"addtime desc"));
        $userNewArr = array();
        foreach($userNews as $k=>$userNew)
        {
            $userNewArr[$k] = $userNew->toArray();
            $userNewArr[$k]['content'] = mb_substr(strip_tags($userNew->content),0,50,'utf-8');
        }
        $data['newsList'] = $userNewArr;
        $data['newsCount'] = Usernews::count(array("uid=$uid and status=0"));


        //动态
        $limit = 20;
        $userDynamics = Userdynamic::find(array("uid=$uid and status=1","limit"=>$limit,"order"=>"addtime desc"));
        $dynamics = array();
        foreach($userDynamics as $k=>$userDynamic)
        {
            $dynamics[$k]['dynamic'] = $userDynamic->toArray();
            $dynamics[$k]['comment'] = array();
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
        $data['dynamicList'] = $dynamics;

        $this->view->data = $data;
    }
    /*资源预览页*/
    public function fileAction($fid)
    {
        if($fid == 0){
            $this->response->redirect("index");
        }else{
            $file_id = $fid;
            $fileInfo = Userfile::findFirst(array("id = $file_id"));
            $fileInfo->file_size = $this->fileSizeConv($fileInfo->file_size);
            $fileInfo->addtime = $this->dateConv(date('Y-m-d',$fileInfo->addtime));
            $this->view->fileInfo = $fileInfo;
        }
    }
    /*个人主页--资源*/
    public function zoneResourceAction()
    {
        if($this->bSignedIn){
        }
        else{
            $this->response->redirect("index");
        }
    }
    /*个人主页--直播*/
    public function zoneLiveAction()
    {
        if($this->bSignedIn){
        }
        else{
            $this->response->redirect("index");
        }
    }
    /*个人主页--直播*/
    public function zoneMicoAction()
    {
        if($this->bSignedIn){
        }
        else{
            $this->response->redirect("index");
        }
    }
    /*个人主页--课程*/
    public function zoneCourseAction()
    {
        if($this->bSignedIn){
        }
        else{
            $this->response->redirect("index");
        }
    }
    /*个人主页--活动*/
    public function zoneActivityAction()
    {
        if($this->bSignedIn){
        }
        else{
            $this->response->redirect("index");
        }
    }
}