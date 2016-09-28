<?php
namespace Cloud\API\Controllers;
use Cloud\Models\Live;
use Cloud\Models\Userfile;
use Cloud\Models\Files;
use Cloud\Models\Userfilepush;
use Cloud\Models\Userfileshare;
use Cloud\Models\Userfollow;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\StringLength;
use Phalcon\Validation\Validator\InclusionIn;
use Phalcon\Mvc\Model\Resultset\Simple as Resultset;

/**
 * @brief 获取资源接口
 */
class SourceController extends ControllerBase
{
    /**
     * @brief 获取图片文件缩略图  参数传递：GET
     * @param file_id 图片文件ID (数字）
     * @param width 图片宽度（数字）
     * @param height 图片高度（数字）
     * @param type 发布预览  默认：0  1：从公共资源去取
     */
    public function getImageThumbAction($file_id,$width=100,$height=100)
    {
        do {
            $file_id = (int)$file_id;
            $file = Userfile::findFirst(array("id=$file_id and (file_type=".\FileType::IMAGE." or file_type=".\FileType::VIDEO.")"));
            if(!$file)
            {
                $this->responseData = array("code"=>\Code::ERROR,"msg"=>"图片不存在","line"=>__LINE__,"data"=>array());
                break;
            }
            $file_data = Files::findFirst(array("id=".$file->file_id));
            if(!$file_data)
            {
                $this->responseData = array("code"=>\Code::ERROR,"msg"=>"原图片不存在","line"=>__LINE__,"data"=>array());
                break;
            }
            if(!is_dir($this->config->upload_thumb_image.$file_data->path))
            {
                mkdir($this->config->upload_thumb_image.$file_data->path,0755,true);
            }
            $defaultName = $file->file_name.'_'.$file->uid.'_'.$width.'_'.$height;
            $ext = strrchr($file->file_name, '.');
            if(!file_exists($this->config->upload_thumb_image.$file_data->path.'/'.$defaultName))
            {
                if($file->file_type==\FileType::VIDEO)
                {
                    $file_path = $this->config->upload_video_thumb.'/'.$file->video_thumb;
                }
                else
                {
                    if($file->file_status==0)
                    {
                        $file_path = $this->config->upload_path.$file->uid.$file->path.$file->file_name;
                    }
                    else if($file->file_status==1)
                    {
                        $file_path = $this->config->upload_recoverpool.$file->uid.$file->path.$file->file_name;
                    }
                    else
                    {
                        $this->responseData = array("code"=>\Code::ERROR,"msg"=>"图片已删除无法预览","line"=>__LINE__,"data"=>array());
                        break;
                    }
                }
                $temp_img = $this->config->upload_thumb_image.$file_data->path.'/'.$file_data->md5.$ext;
                copy($file_path,$temp_img);
                //裁剪图片
                $thumbPath = $this->config->upload_thumb_image.$file_data->path.'/';
                $thumb = new \CThumb();
                $thumb->image = $temp_img;
                $thumb->width = $width;
                $thumb->height = $height;
                $thumb->directory = $thumbPath;
                $thumb->defaultName = $defaultName;
                $thumb->mode = 4;
                $thumb->createThumb();
                $thumb->save();
                @unlink($temp_img);
            }
            $originFile = $this->config->upload_thumb_image.$file_data->path.'/'.$defaultName;
            $size = filesize($originFile);
            $last_modified_time = filemtime($originFile);
            $etag = md5_file($originFile);
            $this->response->setHeader("Content-Type", "image/jpg");
            $this->response->setHeader("Content-Length", $size);
            $this->response->setHeader("Last-Modified", gmdate("D, d M Y H:i:s", $last_modified_time)." GMT");
            $this->response->setHeader("Etag", $etag);
            $this->response->setHeader("Cache-control", 'max-age=86400');
            if (@strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']) == $last_modified_time ||
                @trim($_SERVER['HTTP_IF_NONE_MATCH']) == $etag) {
                $this->response->setStatusCode(304, "Not Modified");
                $this->response->send();
                exit;
            }
            $content = readfile($originFile);
            $this->response->setContent($content);
        }while(false);
    }

    public function downloadExcelAction()
    {
        $file_path = APP_PATH.'/public/excel/model.xls';
        do {
            $fp=fopen($file_path,"r");
            $file_size=filesize($file_path);
            //下载文件需要用到的头
            Header("Content-type: application/octet-stream");
            Header("Accept-Ranges: bytes");
            Header("Accept-Length:".$file_size);
            Header("Content-Disposition: attachment; filename=model.xls");
            $buffer=1024;
            $file_count=0;
            //向浏览器返回数据
            while(!feof($fp) && $file_count<$file_size){
                $file_con=fread($fp,$buffer);
                $file_count+=$buffer;
                echo $file_con;
            }
            fclose($fp);
            exit;
        }while(false);
    }

    public function uploadPicAction()
    {
        do{
            if(!$this->checkUserLogin())
            {
                break;
            }
            $uid = $this->uid;
            if ($this->request->hasFiles()) {
                $data = array();
                $time = date('Y-m-d');
                $FilePathRoot = $this->config->upload_path.'upload/common_image/'.$uid.'/'.$time;

                if(!file_exists($FilePathRoot)){
                    mkdir($FilePathRoot,0755,true);
                }
                foreach ($this->request->getUploadedFiles() as $file) {
                    $ext = strrchr($file->getName(), '.');
                    $name =  '_'.time().$ext;
                    $status= $file->moveTo($FilePathRoot .'/'.$name);
                    if($status){
                        $data[] = array('name'=>'/upload/common_image/'.$uid.'/'.$time.'/'.$name);
                    }else{
                        $data = array('code'=>1, 'data'=>array(), 'line'=>__LINE__, 'msg'=>'upload fail');
                    }
                }
                $this->responseData['data'] = $data;
            }
        }while(false);
        $this->output();
    }

    /**
     * @brief 获取公开图片文件缩略图  参数传递：GET
     * @param file_id 图片文件ID (数字）
     * @param width 图片宽度（数字）
     * @param height 图片高度（数字）
     * @param type 发布预览  默认：0  1：从公共资源去取
     */
    public function getPublicImageThumbAction($file_id,$width=100,$height=100)
    {
        do {
            $file_id = (int)$file_id;
            $file = Userfilepush::findFirst(array("user_file_id=$file_id and (file_type=".\FileType::IMAGE." or file_type=".\FileType::VIDEO.")"));
            if(!$file)
            {
                $this->responseData = array("code"=>\Code::ERROR,"msg"=>"图片不存在","line"=>__LINE__,"data"=>array());
                break;
            }
            $userFile = Userfile::findFirst(array("id=$file_id"));

            $file_data = Files::findFirst(array("id=".$userFile->file_id));
            if(!$file_data)
            {
                $this->responseData = array("code"=>\Code::ERROR,"msg"=>"原图片不存在","line"=>__LINE__,"data"=>array());
                break;
            }
            if(!is_dir($this->config->upload_thumb_image.$file_data->path))
            {
                mkdir($this->config->upload_thumb_image.$file_data->path,0755,true);
            }
            $defaultName = $file->push_file_name.'_'.$file->uid.'_'.$width.'_'.$height;
            $ext = strrchr($file->push_file_name, '.');
            if(!file_exists($this->config->upload_thumb_image.$file_data->path.'/'.$defaultName))
            {
                if($file->file_type==\FileType::VIDEO)
                {
                    $file_path = $this->config->upload_video_thumb.'/'.$userFile->video_thumb;
                }
                else
                {
                    $file_path = $this->config->upload_publicpool.$file->uid.'/'.$file->push_date_folder.'/'.$file->push_file_name;
                }
                $temp_img = $this->config->upload_thumb_image.$file_data->path.'/'.$file_data->md5.$ext;
                copy($file_path,$temp_img);
                //裁剪图片
                $thumbPath = $this->config->upload_thumb_image.$file_data->path.'/';
                $thumb = new \CThumb();
                $thumb->image = $temp_img;
                $thumb->width = $width;
                $thumb->height = $height;
                $thumb->directory = $thumbPath;
                $thumb->defaultName = $defaultName;
                $thumb->mode = 4;
                $thumb->createThumb();
                $thumb->save();
                @unlink($temp_img);
            }
            $originFile = $this->config->upload_thumb_image.$file_data->path.'/'.$defaultName;
            $size = filesize($originFile);
            $last_modified_time = filemtime($originFile);
            $etag = md5_file($originFile);
            $this->response->setHeader("Content-Type", "image/jpg");
            $this->response->setHeader("Content-Length", $size);
            $this->response->setHeader("Last-Modified", gmdate("D, d M Y H:i:s", $last_modified_time)." GMT");
            $this->response->setHeader("Etag", $etag);
            $this->response->setHeader("Cache-control", 'max-age=86400');
            if (@strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']) == $last_modified_time ||
                @trim($_SERVER['HTTP_IF_NONE_MATCH']) == $etag) {
                $this->response->setStatusCode(304, "Not Modified");
                $this->response->send();
                exit;
            }
            $content = readfile($originFile);
            $this->response->setContent($content);
        }while(false);
    }

    /**
     * @brief 获取直播二维码图  参数传递：GET
     * @param live_id 直播ID (数字）
     */
    public function getImageCodeAction($live_id)
    {
        do {
            $live_id = (int)$live_id;
            $live = Live::findFirst(array("id=$live_id and publish_type=1"));
            if(!$live)
            {
                $this->responseData = array("code"=>\Code::ERROR,"msg"=>"直播不存在","line"=>__LINE__,"data"=>array());
                break;
            }
            else
            {
                $url="http://edu.iguangj.com/live/wap/index/".$live_id;
                $qrCode = new \QRC();
                $qrCode->set('url',$url);
                $qrCode->init();
                exit;
            }
        }while(false);
    }

    /**
     * @brief 获取预览图片  参数传递：GET
     * @param file_id 文件ID (数字）
     */
    public function getPreviewImageAction($file_id,$image_id)
    {
        do {
            $file_id = (int)$file_id;
            $image_id = (int)$image_id;
            $file = Userfile::findFirst(array("id=$file_id and file_type=".\FileType::DOC));
            if(!$file)
            {
                $this->responseData = array("code"=>\Code::ERROR,"msg"=>'文件不存在',"line"=>__LINE__,"data"=>array());
                break;
            }
            $uid = 0;
            if($this->checkUserLogin())
            {
                $uid = $this->uid;
            }
            if($file->uid != $uid)
            {
                //判断文件是否是公开资源
                $push = Userfilepush::findFirst(array("user_file_id=".$file->id));
                if(!$push)
                {
                    $userShare = Userfileshare::findFirst(array("user_file_id=".$file->id));
                    if(!$userShare)
                    {
                        //判断是否是个人空间可见文件
                        if($file->visible==1)
                        {
                            $userFollow = Userfollow::findFirst(array("uid=$uid and tuid=".$file->uid));
                            if(!$userFollow)
                            {
                                $this->responseData = array("code"=>\Code::ERROR,"msg"=>'无权限预览文件',"line"=>__LINE__,"data"=>array());
                                break;
                            }
                        }
                        else
                        {
                            $this->responseData = array("code"=>\Code::ERROR,"msg"=>'无权限预览文件',"line"=>__LINE__,"data"=>array());
                            break;
                        }
                    }
                }
            }
            $originFile = $this->config->upload_previewpool.$file->uid.'/'.$file->id.'/'.$image_id.'.jpg';
            if(!file_exists($originFile))
            {
                $this->responseData = array("code"=>\Code::ERROR,"msg"=>'文件不存在',"line"=>__LINE__,"data"=>array());
                break;
            }
            $size = filesize($originFile);
            $last_modified_time = filemtime($originFile);
            $etag = md5_file($originFile);
            $this->response->setHeader("Content-Type", "image/jpg");
            $this->response->setHeader("Content-Length", $size);
            $this->response->setHeader("Last-Modified", gmdate("D, d M Y H:i:s", $last_modified_time)." GMT");
            $this->response->setHeader("Etag", $etag);
            $this->response->setHeader("Cache-control", 'max-age=86400');
            if (@strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']) == $last_modified_time ||
                @trim($_SERVER['HTTP_IF_NONE_MATCH']) == $etag) {
                $this->response->setStatusCode(304, "Not Modified");
                $this->response->send();
                exit;
            }
            $content = readfile($originFile);
            $this->response->setContent($content);
        }while(false);
    }
}
