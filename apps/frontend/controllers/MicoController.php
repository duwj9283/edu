<?php
namespace Cloud\Frontend\Controllers;

use Cloud\Models\Coursewares;
use Cloud\Models\Subject;
use Cloud\Models\Mlesson;
use Cloud\Models\Userfile;
use Cloud\Models\Mlessonstudy;
use Cloud\Models\Mlessoncomment;
use Cloud\Models\Siteconfig;
class MicoController extends ControllerBase
{
    public function indexAction()
    {
        if ($this->bSignedIn) {

        } else {
            $this->response->redirect("login/login");
        }
    }

    /*微课编辑*/
    public function editAction($id=0)
    {
        if ($this->bSignedIn) {
            $this->view->id = $id;

        } else {
            $this->response->redirect("login/login");
        }

    }

    /*微课学习*/
    public function videoAction()
    {
        if ($this->bSignedIn) {

        } else {
            $this->response->redirect("login/login");
        }
    }

    /*微课学习*/
    public function showAction($id=0)
    {
        if ($this->bSignedIn) {
            $videoInfo = Coursewares::findFirst($id);
            if(!$videoInfo)
            {
                $this->response->redirect("/");
                return;
            }
            $this->view->info = $videoInfo;
        } else {
            $this->response->redirect("login/login");
        }
    }

    /*微课学习*/
    public function studyAction()
    {
        if ($this->bSignedIn) {

        } else {
            $this->response->redirect("login/login");
        }
    }
    /*微课前端公共资源*/
    public function publistAction()
    {
        $subjects = Subject::find(array("father_id>0 and visible=1","order"=>"id asc","limit"=>8));
        $subjectArr = array();
        foreach($subjects as $k=>$subject)
        {
            $subjectArr[$k] = $subject->toArray();
            $mlessonCount = Mlesson::count(array("subject_id=".$subject->id));
            $subjectArr[$k]['mlessonCount'] = $mlessonCount;
        }
        $this->view->subjects = $subjectArr;

        //幻灯片
        $siteConfig = Siteconfig::findFirst(array("option_title='site_banner4'"));
        $this->view->images = explode('|',$siteConfig->option_value);
    }
    /*微课详情页面*/
    public function detailAction($m_lesson_id)
    {
        $lesson = Mlesson::findFirst(array("id=$m_lesson_id"));
        if(!$lesson)
        {
            $this->response->redirect("/index");
            return;
        }
        //微课信息
        $this->view->lesson = $lesson->toArray();
        $this->view->teacher = $lesson->getUserInfo()->toArray();

        //学科专业
        $subject = Subject::findFirst(array("id=".$lesson->subject_id));
        $this->view->child_subject = $subject->subject_name;
        $subjectF = Subject::findFirst(array("id=".$subject->father_id));
        $this->view->father_subject = $subjectF->subject_name;
        //微课评价
        $lessonComments = MLessoncomment::find(array("m_lesson_id=$m_lesson_id"));
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
        $this->view->comment_list = $lessonInfos;
        //评论数量
        $this->view->comment_count = $comment_count;

        //微课相关资料
        $user_files = array();
        if(!empty($lesson->file_ids))
        {
            $userFiles = Userfile::find(array("id in (".$lesson->file_ids.")"));
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
                if($dataSuffix===".xml"){
                    $type='xml';
                }
                $user_files[$key]['ext'] = $type;
                $fileSize = $this->fileSizeConv($userFile->file_size);
                $user_files[$key]['fileSize'] = $fileSize;
            }
        }
        $this->view->relation_file_list = $user_files;
        //学习人数
        $lessonCount = Mlessonstudy::count(array("m_lesson_id=$m_lesson_id"));
        $this->view->study_count = $lessonCount;
    }
    /*微课播放*/
    public function playAction($m_lesson_id)
    {
        if ($this->bSignedIn) {
            $lesson = Mlesson::findFirst(array("id=$m_lesson_id"));
            if(!$lesson)
            {
                $this->response->redirect("login/login");
                return;
            }
            //微课信息
            $this->view->lesson = $lesson->toArray();
            $this->view->teacher = $lesson->getUserInfo()->toArray();

            //学科专业
            $subject = Subject::findFirst(array("id=".$lesson->subject_id));
            $this->view->child_subject = $subject->subject_name;
            $subjectF = Subject::findFirst(array("id=".$subject->father_id));
            $this->view->father_subject = $subjectF->subject_name;
            //微课评价
            $lessonComments = MLessoncomment::find(array("m_lesson_id=$m_lesson_id"));
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
            $this->view->comment_list = $lessonInfos;
            //评论数量
            $this->view->comment_count = $comment_count;

            //微课相关资料
            $user_files = array();
            if(!empty($lesson->file_ids))
            {
                $userFiles = Userfile::find(array("id in (".$lesson->file_ids.")"));
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
                    if($dataSuffix===".xml"){
                        $type='xml';
                    }
                    $user_files[$key]['ext'] = $type;
                    $fileSize = $this->fileSizeConv($userFile->file_size);
                    $user_files[$key]['fileSize'] = $fileSize;
                }
            }
            $this->view->relation_file_list = $user_files;
            //学习人数
            $lessonCount = Mlessonstudy::count(array("m_lesson_id=$m_lesson_id"));
            $this->view->study_count = $lessonCount;

            //课件
            $file = Userfile::findFirst(array("id=".$lesson->file));
            $this->view->path = $file->uid.$file->path.$file->file_name;
            $this->view->ext = substr(strrchr($file->file_name,'.'),1);
        } else {
            $this->response->redirect("login/login?backurl=".$_SERVER['REQUEST_URI']);
        }
    }
}