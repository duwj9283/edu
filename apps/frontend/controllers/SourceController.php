<?php
namespace Cloud\Frontend\Controllers;
use Cloud\Models\Activity;
use Cloud\Models\Group;
use Cloud\Models\Lesson;
use Cloud\Models\Lessonlist;
use Cloud\Models\Live;
use Cloud\Models\Mlesson;
use Cloud\Models\Subject;
use Cloud\Models\Userinfo;

class SourceController extends ControllerBase
{
    public function uploadPicAction()
    {
        do{
            if ($this->request->hasFiles()) {
                $time = date('Y-m-d');
                $FilePathRoot = $this->config->upload_path.'upload/common_image/'.$this->user->uid.'/'.$time;
                if(!file_exists($FilePathRoot)){
                    mkdir($FilePathRoot,0755,true);
                }
                foreach ($this->request->getUploadedFiles() as $file) {
                    $ext = strrchr($file->getName(), '.');
                    $name =  '_'.time().$ext;
                    $status= $file->moveTo($FilePathRoot .'/'.$name);
                    if($status){
                        $data = array('name'=>'/upload/common_image/'.$this->user->uid.'/'.$time.'/'.$name);
                        $this->set_data($data);
                    }else{
                        $data = array('code'=>1, 'data'=>array(), 'line'=>__LINE__, 'msg'=>'upload fail');
                        $this->set_output($data);
                    }
                }
            }

        }while(false);
        $this->output();
    }

    public function getFrontImageThumbAction($type='lesson',$id,$width=100,$height=100)
    {
        do{
            $id = (int)$id;
            if($type == 'lesson')
            {
                $lesson = Lesson::findFirst(array("id=$id"));
                if(!$lesson)
                {
                    $this->responseData = array("code"=>\Code::ERROR,"msg"=>"图片不存在","line"=>__LINE__,"data"=>array());
                    break;
                }
                $file = $lesson->pic;
            }
            if($type == 'mlesson')
            {
                $mLesson = Mlesson::findFirst(array("id=$id"));
                if(!$mLesson)
                {
                    $this->responseData = array("code"=>\Code::ERROR,"msg"=>"图片不存在","line"=>__LINE__,"data"=>array());
                    break;
                }
                $file = $mLesson->pic;
            }
            else if($type == 'activity')
            {
                $activity = Activity::findFirst(array("id=$id"));
                if(!$activity)
                {
                    $this->responseData = array("code"=>\Code::ERROR,"msg"=>"图片不存在","line"=>__LINE__,"data"=>array());
                    break;
                }
                $file = $activity->cover_pic;
            }
            else if($type == 'live')
            {
                $live = Live::findFirst(array("id=$id"));
                if(!$live)
                {
                    $this->responseData = array("code"=>\Code::ERROR,"msg"=>"图片不存在","line"=>__LINE__,"data"=>array());
                    break;
                }
                $file = $live->cover_pic;
            }
            else if($type == 'header')
            {
                $userInfo = Userinfo::findFirst(array("uid=$id"));
                if(!$userInfo)
                {
                    $this->responseData = array("code"=>\Code::ERROR,"msg"=>"图片不存在","line"=>__LINE__,"data"=>array());
                    break;
                }
                $file = $userInfo->headpic;
            }
            else if($type == 'group'){
                $group = Group::findFirst(array("gid=$id"));
                if(!$group){
                    $this->responseData = array("code"=>\Code::ERROR,"msg"=>"图片不存在","line"=>__LINE__,"data"=>array());
                    break;
                }
                $file = $group->headpic;
            }

            $fileInfoArray = explode('/',$file);
            $fileDate = $fileInfoArray[4];
            $fileName = $fileInfoArray[5];
            $fileUid = $fileInfoArray[3];
            $md5Name = md5($fileName);
            if(!is_dir($this->config->upload_image_thumb.$fileDate))
            {
                mkdir($this->config->upload_image_thumb.$fileDate,0755,true);
            }

            $defaultName = $fileName.'_'.$fileUid.'_'.$width.'_'.$height;
            $ext = strrchr($file, '.');
            if(!file_exists($this->config->upload_image_thumb.$fileDate.'/'.$defaultName))
            {
                $file_path = $this->config->upload_path.$file;
                $temp_img = $this->config->upload_image_thumb.$fileDate.'/'.$md5Name.$ext;
                copy($file_path,$temp_img);
                //裁剪图片
                $thumbPath = $this->config->upload_image_thumb.$fileDate.'/';
                $thumb = new \CThumb();
                $thumb->image = $temp_img;
                $thumb->width = $width;
                $thumb->height = $height;
                $thumb->directory = $thumbPath;
                $thumb->defaultName = $defaultName;
                $thumb->mode = 1;
                $thumb->createThumb();
                $thumb->save();
                @unlink($temp_img);
            }
            $originFile = $this->config->upload_image_thumb.$fileDate.'/'.$defaultName;
            $size = filesize($originFile);
            $last_modified_time = filemtime($originFile);
            $etag = md5_file($originFile);
            $this->response->setHeader("Content-Type", "image/jpg");
            $this->response->setHeader("Content-Length", $size);
            $this->response->setHeader("Last-Modified", gmdate("D, d M Y H:i:s", $last_modified_time)." GMT");
            $this->response->setHeader("Etag:", $etag);
            if (@strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']) == $last_modified_time ||
                @trim($_SERVER['HTTP_IF_NONE_MATCH']) == $etag) {
                header("HTTP/1.1 304 Not Modified");
                exit;
            }
            $content = readfile($originFile);
            $this->response->setContent($content);
        }while(false);
    }


    public function getSubjectAction(){
        do{
            $father_id = $this->request->getPost('father_id');
            $father_id = isset($father_id)?(int)$father_id:0;
            $keywords = $this->request->getPost('keywords');
            $keywords = isset($keywords)?trim($keywords):'';
            $condition = 'visible=1';
            if($father_id>0)
            {
                $condition .= " and father_id=$father_id";
            }
            else
            {
                if($keywords)
                {
                    $condition .= " and subject_name like '%$keywords%' and father_id=0";
                }
            }
            $subjectObject = Subject::find(array($condition));
            $subject = $subjectObject->toArray();
            $subjectArray = array();
            $subjectChild = array();
            foreach($subject as $key=>$value){
                  if($value['father_id'] == 0){
                      $subjectArray[$value['id']] = $value;
                  }else{
                      $subjectChild[$value['father_id']][] =$value;
                  }
            }
            foreach($subjectChild as $key=>$value){
                $subjectArray[$key]['child'] = $value;
            }
            $this->set_data($subjectArray);

        }while(false);
        $this->output();
    }

    public function uploadExcelAction()
    {
        do{
            if ($this->request->hasFiles()) {
                $time = date('Y-m-d');
                $FilePathRoot = $this->config->upload_root.'upload/excel/'.$this->user->uid.'/'.$time;
                if(!file_exists($FilePathRoot)){
                    mkdir($FilePathRoot,0755,true);
                }
                foreach ($this->request->getUploadedFiles() as $file) {
                    $ext = strrchr($file->getName(), '.');
                    $name =  time().$ext;
                    $status= $file->moveTo($FilePathRoot .'/'.$name);
                    if($status){
                        $data = array('name'=>'/upload/excel/'.$this->user->uid.'/'.$time.'/'.$name);
                        $this->set_data($data);
                    }else{
                        $data = array('code'=>1, 'data'=>array(), 'line'=>__LINE__, 'msg'=>'upload fail');
                        $this->set_output($data);
                    }
                }
            }

        }while(false);
        $this->output();
    }
}