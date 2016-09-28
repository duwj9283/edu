<?php
namespace Cloud\API\Controllers;
use Cloud\Models\Converttask;
use Cloud\Models\Groupfile;
use Cloud\Models\Live;
use Cloud\Models\Livevideoinfo;
use Cloud\Models\Subject;
use Cloud\Models\Touserfile;
use Cloud\Models\Files;
use Cloud\Models\Userfile;
use Cloud\Models\Userfilecollect;
use Cloud\Models\Userfilecomment;
use Cloud\Models\Userfiledownload;
use Cloud\Models\Userfileinfo;
use Cloud\Models\Userfilepush;
use Cloud\Models\Userfileshare;
use Cloud\Models\Groupuser;
use Cloud\Models\Userdynamic;
use Cloud\Models\Userfollow;
use Cloud\Models\Userinfo;
use Cloud\Models\Usercapacity;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\StringLength;
use Phalcon\Validation\Validator\InclusionIn;
use Phalcon\Mvc\Model\Resultset\Simple as Resultset;

/**
 * @brief 文件数据接口
 */
class FileController extends ControllerBase
{
    /**
     * @brief 【分页】获取目录的用户文件&文件夹
     * @param path 文件相对路径；例：/  /{文件夹ID}/
     * @param （选填）page 当前页 默认是：1
     * @param （选填）limit 每页显示条数 默认是：1
     * @param （选填）type 文件类型 默认是：0（全部）
     * @param （选填）sort 资源排序1：按时间降序（默认）2：按时间升序 3：按文件名升序 4:按文件名降序5:按文件大小降序6:按文件大小升序
     * @param （选填）keywords 名称搜索关键词
     */
    public function getFilesByPageAction()
    {
//        $this->params = array('path'=>'/','type'=>0,'page'=>1,'limit'=>12);
        do {
            $this->validation->add('path', new PresenceOf(array('message'=>'参数缺失:path')));
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
            $path = $this->params['path'];
            $limit = isset($this->params['limit'])&&(int)$this->params['limit']>1?(int)$this->params['limit']:10;
            $page = isset($this->params['page'])&&(int)$this->params['page']>0?(int)$this->params['page']:1;
            $type = isset($this->params['type'])?(int)$this->params['type']:0;
            $sort = isset($this->params['sort'])&&(int)$this->params['sort']>0?$this->params['sort']:\FileSortType::DOWNTIME;
            $keywords = isset($this->params['keywords'])?$this->params['keywords']:null;
            $condition = '';
            $where_fold = "uid=$uid and path='$path' and file_status=".\FileStatus::NORMAL." and file_type=".\FileType::FOLDER;
            $where_file = "uid=$uid and percent=100 and file_status=".\FileStatus::NORMAL." and file_type>".\FileType::FOLDER;
            $offset = ($page - 1) * $limit;
            if(!empty($keywords))
            {
                $condition = " file_name like '%$keywords%' and ";
            }
            if($type>0)
            {
                $total_rows = Userfile::count(array("$condition $where_file and file_type=".$type));
                $userFiles = Userfile::find(array("$condition $where_file and file_type=".$type,"limit"=>$limit,"offset"=>$offset,"order"=>\FileSortType::$TYPES[$sort]));
                $this->responseData['data']['page'] = $page;
                $this->responseData['data']['total_rows'] = $total_rows;
                $this->responseData['data']['page_count'] = ceil($total_rows / $limit);
                $data = $userFiles->toArray();
            }
            else
            {
                if(!empty($condition))
                {
                    $fold_cnt = Userfile::count(array($where_fold));//目录总数
                    $file_cnt = Userfile::count(array("$where_file and path='$path'")); //文件总数
                    $total_rows = $fold_cnt + $file_cnt; // 目录和文件总和
                    $this->responseData['data']['page'] = $page;
                    $this->responseData['data']['total_rows'] = $total_rows;
                    $this->responseData['data']['page_count'] = ceil($total_rows / $limit);
                    $folder_rows = Userfile::find(array("$condition $where_fold","limit"=>$limit,"offset"=>$offset,"order"=>\FileSortType::$TYPES[$sort]));
                    $residue = $limit - count($folder_rows);
                    $file_rows = array();
                    if ($residue > 0) {
                        $offset = (($page - 1) * $limit - $fold_cnt)>0?(($page - 1) * $limit - $fold_cnt):0;
                        $file_rows = Userfile::find(array("$condition $where_file and path='$path'","limit"=>$residue,"offset"=>$offset,"order"=>\FileSortType::$TYPES[$sort]));
                    }
                    $data = array_merge($folder_rows->toArray(),$file_rows->toArray());
                }
                else
                {
                    $fold_cnt = Userfile::count(array($where_fold));//目录总数
                    $file_cnt = Userfile::count(array("$where_file and path='$path'")); //文件总数

                    $total_rows = $fold_cnt + $file_cnt; // 目录和文件总和
                    $this->responseData['data']['page'] = $page;
                    $this->responseData['data']['total_rows'] = $total_rows;
                    $this->responseData['data']['page_count'] = ceil($total_rows / $limit);
                    $folder_rows = Userfile::find(array($where_fold,"limit"=>$limit,"offset"=>$offset,"order"=>\FileSortType::$TYPES[$sort]));

                    $residue = $limit - count($folder_rows);
                    $file_rows = array();
                    if ($residue > 0) {

                        $offset = (($page - 1) * $limit - $fold_cnt)>0?(($page - 1) * $limit - $fold_cnt):0;
                        $file_rows = Userfile::find(array("$where_file and path='$path'","limit"=>$residue,"offset"=>$offset,"order"=>\FileSortType::$TYPES[$sort]));
                        $data = array_merge($folder_rows->toArray(),$file_rows->toArray());
                    }
                    else
                    {
                        $data = $folder_rows->toArray();
                    }
                }
            }
            foreach($data as $k=>$v)
            {
                $data[$k] = $v;
                $data[$k]['sizeConv'] = $this->fileSizeConv($v['file_size']);
                $push = Userfilepush::findFirst(array("user_file_id=".$v['id']));
                $data[$k]['is_push'] = -1;
                if($push)
                {
                    if($push->status==0)
                    {
                        $data[$k]['is_push'] = 0;
                    }
                    else
                    {
                        $data[$k]['is_push'] = 1;
                    }
                }
            }
            $this->responseData['data']['userFiles'] = $data;
        }while(false);
        $this->output();
    }

    /**
     * @brief 【分页】我的录像
     * @param （选填）page 当前页 默认是：1
     * @param （选填）limit 每页显示条数 默认是：1
     * @param （选填）sort 资源排序1：按时间降序（默认）2：按时间升序 3：按文件名升序 4:按文件名降序5:按文件大小降序6:按文件大小升序
     * @param （选填）keywords 名称搜索关键词
     */
    public function getVideoFilesByPageAction()
    {
//        $this->params = array('path'=>'/','type'=>0,'page'=>1,'limit'=>12);
        do {
            if(!$this->checkUserLogin())
            {
                break;
            }
            $uid = $this->uid;
            $limit = isset($this->params['limit'])&&(int)$this->params['limit']>1?(int)$this->params['limit']:10;
            $page = isset($this->params['page'])&&(int)$this->params['page']>0?(int)$this->params['page']:1;
            $sort = isset($this->params['sort'])&&(int)$this->params['sort']>0?$this->params['sort']:\FileSortType::DOWNTIME;
            $keywords = isset($this->params['keywords'])?$this->params['keywords']:null;
            $condition = '';
            $where_file = "uid=$uid and percent=100 and file_status=".\FileStatus::NORMAL." and file_type>".\FileType::FOLDER;
            $offset = ($page - 1) * $limit;
            if(!empty($keywords))
            {
                $condition = " file_name like '%$keywords%' and ";
            }
            $total_rows = Userfile::count(array("$condition $where_file and is_video=1"));
            $userFiles = Userfile::find(array("$condition $where_file and is_video>0","limit"=>$limit,"offset"=>$offset,"order"=>\FileSortType::$TYPES[$sort]));
            $this->responseData['data']['page'] = $page;
            $this->responseData['data']['total_rows'] = $total_rows;
            $this->responseData['data']['page_count'] = ceil($total_rows / $limit);
            $data = $userFiles->toArray();
            foreach($data as $k=>$v)
            {
                $data[$k] = $v;
                $data[$k]['sizeConv'] = $this->fileSizeConv($v['file_size']);
                $push = Userfilepush::findFirst(array("user_file_id=".$v['id']));
                $data[$k]['is_push'] = -1;
                if($push)
                {
                    if($push->status==0)
                    {
                        $data[$k]['is_push'] = 0;
                    }
                    else
                    {
                        $data[$k]['is_push'] = 1;
                    }
                }
            }
            $this->responseData['data']['userFiles'] = $data;
        }while(false);
        $this->output();
    }

    /**
     * @brief 获取目录的用户文件&文件夹
     * @param path 文件相对路径；例：/  /{文件夹ID}/
     * @param page 当前页 默认是：1
     * @param type 文件类型（选填） 默认是：0（全部）
     * @param sort 资源排序（选填）1：按时间降序（默认）2：按时间升序 3：按文件名升序 4:按文件名降序5:按文件大小降序6:按文件大小升序
     * @param keywords 名称搜索关键词（选填）
     */
    public function getFilesAction()
    {
//        $this->params = array('path'=>'/','page'=>1,'type'=>0,'sort'=>5);
        do {
            $this->validation->add('path', new PresenceOf(array('message'=>'参数缺失:path')));
            $this->validation->add('page', new PresenceOf(array('message'=>'参数缺失:page')));
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

            $path = $this->params['path'];
            $page = (int)$this->params['page'];
            $type = isset($this->params['type'])?(int)$this->params['type']:0;
            $sort = isset($this->params['sort'])&&(int)$this->params['sort']>0?$this->params['sort']:\FileSortType::DOWNTIME;
            $keywords = isset($this->params['keywords'])?$this->params['keywords']:null;
            $condition = '';
            if(!empty($keywords))
            {
                $condition = " file_name like '%$keywords%' and file_type>".\FileType::FOLDER." and ";
            }
            $counter = 12;  //默认单次返回的数据数量
            $start = ($page-1)*$counter;
            if($type>0)
            {
                $userFiles = Userfile::find(array("$condition uid=$uid and percent=100 and file_status=".\FileStatus::NORMAL." and file_type=".$type,"order"=>\FileSortType::$TYPES[$sort]));
                $data = $userFiles->toArray();
            }
            else
            {
                if(!empty($condition))
                {
                    $userFiles = Userfile::find(array("$condition uid=$uid and path='$path' and percent=100 and file_status=".\FileStatus::NORMAL,"order"=>\FileSortType::$TYPES[$sort]));
                    $data = $userFiles->toArray();
                }
                else
                {
                    $userFolder = Userfile::find(array("uid=$uid and path='$path' and file_status=".\FileStatus::NORMAL." and file_type=".\FileType::FOLDER));

                    $userFiles = Userfile::find(array("uid=$uid and path='$path' and percent=100 and file_status=".\FileStatus::NORMAL." and file_type>".\FileType::FOLDER,"order"=>\FileSortType::$TYPES[$sort]));

                    $data = array_merge($userFolder->toArray(),$userFiles->toArray());
                }
            }
            foreach($data as $k=>$v)
            {
                $data[$k] = $v;
                $data[$k]['sizeConv'] = $this->fileSizeConv($v['file_size']);
            }
            $this->responseData['data']['userFiles'] = $data;
        }while(false);
        $this->output();
    }

    /**
     * @brief 前台资源文件列表
     * @param page 当前页 默认是：1
     * @param （选填） type文件类型 默认是：0（全部）
     * @param subjectID 子集科目ID
     */
    public function getPushFilesAction()
    {
//        $this->params = array('page'=>2,'subjectID'=>0,'type'=>0);
        do {
            $this->validation->add('page', new PresenceOf(array('message'=>'参数缺失:page')));
            $this->validation->add('subjectID', new PresenceOf(array('message'=>'参数缺失:subjectID')));
            $messages = $this->validation->validate($this->params);
            if (count($messages)) {
                foreach ($messages as $message) {
                    array_push($this->errors, strval($message));
                }
                $this->responseData = array("code"=>\Code::ERROR_PARAMS,"msg"=>join(';', $this->errors),"line"=>__LINE__,"data"=>array());
                break;
            }
            $page = (int)$this->params['page'];
            $type = isset($this->params['type'])?(int)$this->params['type']:0;
            $subjectID = isset($this->params['subjectID'])&&(int)$this->params['subjectID']>0?$this->params['subjectID']:0;
            $condition = '';
            if($type>0)
            {
                $condition .= "and file_type = $type";
                if($subjectID>0)
                {
                    $condition .= " and subject_id = $subjectID";
                }
            }
            else
            {
                if($subjectID>0)
                {
                    $condition .= " and subject_id = $subjectID";
                }
            }
            $counter = 12;  //默认单次返回的数据数量
            $start = ($page-1)*$counter;
            $pushFiles = Userfilepush::find(array("status=1 $condition","order"=>"addtime desc,id desc",'limit'=>$counter,'offset'=>$start));
            $fileIds = array();
            $pushFileArr = array();
            $subjectIds = array();
            foreach($pushFiles as $pushFile)
            {
                if(!in_array($pushFile->subject_id,$subjectIds))
                {
                    array_push($subjectIds,$pushFile->subject_id);
                }
                array_push($fileIds,$pushFile->user_file_id);
                $userIds = array();
                if(!in_array($pushFile->uid,$userIds))
                {
                    array_push($userIds,$pushFile->uid);
                }


                $dataSuffix = strrchr($pushFile->push_file_name, '.');
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
                $pushFileArr[$pushFile->user_file_id]['ext'] = $type;
                $pushFileArr[$pushFile->user_file_id]['file_id'] = $pushFile->user_file_id;
                $pushFileArr[$pushFile->user_file_id]['push_file_name'] = $pushFile->push_file_name;
                $pushFileArr[$pushFile->user_file_id]['push_date_folder'] = $pushFile->push_date_folder;
                $pushFileArr[$pushFile->user_file_id]['file_type'] = $pushFile->file_type;
                $pushFileArr[$pushFile->user_file_id]['subject_id'] = $pushFile->subject_id;
                $pushFileArr[$pushFile->user_file_id]['addtime'] = $pushFile->addtime;
                $pushFileArr[$pushFile->user_file_id]['userInfo'] = $pushFile->getUserInfo()->toArray();
                $pushFileArr[$pushFile->user_file_id]['like'] = 0;
                $pushFileArr[$pushFile->user_file_id]['download'] = 0;
            }
            if(!empty($subjectIds))
            {
                $subjects = Subject::find(array('id in ('.join(',',$subjectIds).')'));
                $subjectArr = array();
                foreach($subjects as $subject)
                {
                    $subjectArr[$subject->id] = $subject->subject_name;
                }
                foreach($pushFiles as $pushFile)
                {
                    $pushFileArr[$pushFile->user_file_id]['subject_name'] = $subjectArr[$pushFile->subject_id];
                }
            }
            if(!empty($fileIds))
            {
                //下载数量统计
                $fileDownloads = Userfiledownload::find(array("user_file_id in(".join(',',$fileIds).")"));
                foreach($fileDownloads as $fileDownload)
                {
                    $pushFileArr[$fileDownload->user_file_id]['download']++;
                }
                $userFileInfos = Userfileinfo::find(array("user_file_id in(".join(',',$fileIds).")"));
                foreach($userFileInfos as $userFileInfo)
                {
                    $pushFileArr[$userFileInfo->user_file_id]['file_desc'] = $userFileInfo->desc;
                }
            }

            $pushFileCounter = Userfilepush::count(array("status=1 $condition"));
            $this->responseData['data']['userFiles'] = array_values($pushFileArr);
            $this->responseData['data']['allFileCounter'] = $pushFileCounter;
        }while(false);
        $this->output();
    }

    /**
     * @brief 获取收藏文件
     * @param page 当前页 默认是：1
     * @param type 文件类型（选填） 默认是：0（全部）
     */
    public function getCollectFilesAction()
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
            if(!$this->checkUserLogin())
            {
                break;
            }

            $uid = $this->uid;
            $page = (int)$this->params['page'];
            $type = isset($this->params['type'])?(int)$this->params['type']:0;
            $where_sql = '';
            if($type>0)
            {
                $where_sql = " and t2.file_type=$type";
            }
            $counter = 12;  //默认单次返回的数据数量
            $start = ($page-1)*$counter;

            $sql = "select t2.* from edu_user_file_collect as t1 left join edu_user_file as t2 on t1.user_file_id=t2.id where t1.uid = $uid $where_sql order by t1.addtime desc limit $start,$counter";
            $userFileCollect = new Userfilecollect();
            $userFileCollect = new Resultset(null, $userFileCollect, $userFileCollect->getReadConnection()->query($sql));

            $this->responseData['data']['userFiles'] = $userFileCollect->toArray();
        }while(false);
        $this->output();
    }

    /**
     * @brief 获取目录文件夹
     * @param path 文件相对路径；例：/  /{文件夹ID}/
     */
    public function getFolderAction()
    {
        do {
            $this->validation->add('path', new PresenceOf(array('message'=>'参数缺失:path')));
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

            $path = $this->params['path'];
            $userFiles = Userfile::find(array("uid=$uid and path='$path' and file_status=".\FileStatus::NORMAL." and file_type=".\FileType::FOLDER,"order"=>"addtime desc,id desc"));
            $folders = array();
            foreach($userFiles as $k=>$userFile)
            {
                $folders[$userFile->id] = $userFile->toArray();
                $child_path = $userFile->path.$userFile->id.'/';
                //遍历找出所有子文件
                $sql = "select count(*) as c from edu_user_file where file_status=".\FileStatus::NORMAL." and file_type=".\FileType::FOLDER." and uid=$uid and left(path,".strlen($child_path).") ='".$child_path."'";
                $userChildFileCount = new Userfile();
                $userChildFileCount = new Resultset(null, $userChildFileCount, $userChildFileCount->getReadConnection()->query($sql));
                $userChildFileCount = $userChildFileCount->toArray();
                if($userChildFileCount[0]['c']>0)
                {
                    $folders[$userFile->id]['had_folder'] = 1;
                }
                else
                {
                    $folders[$userFile->id]['had_folder'] = 0;
                }
            }
            $this->responseData['data']['userFolder'] = array_values($folders);
        }while(false);
        $this->output();
    }

    /**
     * @brief 创建目录
     * @param path 文件相对路径；例：/  /{文件夹ID}/
     * @param folder_name 目录名称
     */
    public function createFolderAction()
    {
//        $this->params = array('path'=>'/6/','folder_name'=>'tt');
        do {
            $this->validation->add('path', new PresenceOf(array('message'=>'参数缺失:path')));
            $this->validation->add('folder_name', new PresenceOf(array('message'=>'参数缺失:folder_name')));
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
            $path = $this->params['path'];
            $folder_name = $this->params['folder_name'];

            $userFile = Userfile::findFirst(array("uid=$uid and path='$path' and file_name='$folder_name' and file_type=".\FileType::FOLDER." and file_status=".\FileStatus::NORMAL));
            $new_file_name = $folder_name;
            if($userFile)
            {
                $new_file_name = $this->getLastFileName($uid,0,$folder_name,$path);
            }
            $this->db->begin();
            $userFile = new Userfile();
            $userFile->uid = $uid;
            $userFile->file_name = $new_file_name;
            $userFile->path = $path;
            $userFile->file_from = \FileFrom::PC;
            $userFile->file_status = \FileStatus::NORMAL;
            $userFile->file_type = \FileType::FOLDER;
            $userFile->addtime = time();
            if(!$userFile->create())
            {
                $this->db->rollback();
                $this->responseData = array("code"=>\Code::ERROR_DB,"msg"=>"目录创建失败","line"=>__LINE__,"data"=>array());
                break;
            }
            $dir = $this->config->upload_path.$uid.$path.$userFile->id;
            $f = new \FileUtil();
            if(!$f->createDir($dir))
            {
                $this->db->rollback();
                $this->responseData = array("code"=>\Code::ERROR_DB,"msg"=>"服务器目录创建失败","line"=>__LINE__,"data"=>array());
                break;
            }
            $this->db->commit();
            $this->responseData['data']['id'] = $userFile->id;
            $this->responseData['data']['name'] = $new_file_name;
        }while(false);
        $this->output();
    }

    /**
     * @brief 重命名文件&文件夹
     * @param file_id   文件ID
     * @param name   新文件名
     * @param is_force (选填)是否强制重命名 0：否（默认） 1：是
     */
    public function renameFileAction()
    {
//        $this->params = array('file_id'=>69,'name'=>'QQ短视频20160324145119(1).mp4','is_force'=>1);
        do {
            $this->validation->add('file_id', new PresenceOf(array('message'=>'参数缺失:file_id')));
            $this->validation->add('name', new PresenceOf(array('message'=>'参数缺失:name')));
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
            $file_id = $this->params['file_id'];
            $name = trim($this->params['name']);
            $is_force = isset($this->params['is_force'])?(int)$this->params['is_force']:0;
            $file = Userfile::findFirst(array("id=$file_id and uid=$uid and file_status=".\FileStatus::NORMAL));
            if(!$file)
            {
                $this->responseData = array("code"=>\Code::ERROR,"msg"=>'文件或文件夹不存在',"line"=>__LINE__,"data"=>array());
                break;
            }
            $sameNameFile = Userfile::findFirst(array("path='".$file->path."' and file_name='$name' and uid=$uid and file_status=".\FileStatus::NORMAL." and id!=".$file->id));
            if($sameNameFile)
            {
                if($file->file_type==\FileType::FOLDER)
                {
                    $is_force=1;
                }
                if($is_force==1)    //强制重命名
                {
                    $new_file_name = $this->getLastFileName($uid,$file->id,$name,$file->path);
                }
                else
                {
                    $this->responseData = array("code"=>\Code::ERROR_DB_WRITE,"msg"=>'此目录下已存在同名文件，是否要保存两个文件',"line"=>__LINE__,"data"=>array());
                    break;
                }
            }
            else
            {
                $new_file_name = $name;
            }
            $this->db->begin();
            if($file->file_type==\FileType::FOLDER)
            {
                //重命名文件夹
                $child_path = $file->path.$file->file_name.'/';
                $new_path = $file->path.$new_file_name.'/';

                $sql = "update edu_user_file set path=replace(path,'".$child_path."','".$new_path."') where uid=$uid and left(path,".strlen($child_path).") ='".$child_path."'";

                $userFile = $this->db->query($sql);

                if(!$userFile)
                {
                    $this->db->rollback();
                    $this->responseData = array("code"=>\Code::ERROR,"msg"=>'文件子目录重命名失败',"line"=>__LINE__,"data"=>array());
                    break;
                }
            }
            else
            {
                //重命名文件
                if(!rename($this->config->upload_path.$uid.$file->path.$file->file_name,$this->config->upload_path.$uid.$file->path.$new_file_name))
                {
                    $this->db->rollback();
                    $this->responseData = array("code"=>\Code::ERROR,"msg"=>'文件重命名失败',"line"=>__LINE__,"data"=>array());
                    break;
                }
            }
            $sql = "update edu_user_file set file_name='$new_file_name' where id=$file_id";
            $userFile = $this->db->query($sql);
            if(!$userFile)
            {
                $this->db->rollback();
                $this->responseData = array("code"=>\Code::ERROR,"msg"=>'重命名失败',"line"=>__LINE__,"data"=>array());
                break;
            }
            $this->db->commit();
            $this->responseData['data']['name'] = $new_file_name;
        }while(false);
        $this->output();
    }

    /**
     * @brief 【批量移动】批量移动文件到目录
     * @param file_ids   文件ID
     * @param folder_id 文件夹ID  如果是0：代表根目录
     * @param is_force (选填)是否强制重命名 0：否（默认） 1：是
     */
    public function mvFilesToFolderAction()
    {
//        $this->params = array('file_ids'=>array(54,57),'folder_id'=>56);
        do {
            $this->validation->add('file_ids', new PresenceOf(array('message'=>'参数缺失:file_ids')));
            $this->validation->add('folder_id', new PresenceOf(array('message'=>'参数缺失:folder_id')));
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
            $file_ids = $this->params['file_ids'];
            $folder_id = (int)$this->params['folder_id'];
            $is_force = isset($this->params['is_force'])?(int)$this->params['is_force']:0;

            $files = Userfile::find(array("id in(".join(',',$file_ids).") and file_type>".\FileType::FOLDER." and uid=$uid and file_status=".\FileStatus::NORMAL));
            $file_name_arr = array();
            foreach($files as $file)
            {
                array_push($file_name_arr,"'".$file->file_name."'");
            }
            if($folder_id>0)
            {
                $folder = Userfile::findFirst(array("id=$folder_id and uid=$uid and file_status=".\FileStatus::NORMAL." and file_type=".\FileType::FOLDER));
                if(!$folder)
                {
                    $this->responseData = array("code"=>\Code::ERROR,"msg"=>'文件夹不存在',"line"=>__LINE__,"data"=>array());
                    break;
                }
                $folder_path = $folder->path.$folder->id.'/';
            }
            else
            {
                $folder_path = '/';
            }
            $checkSameNameFile = Userfile::findFirst(array("path='".$folder_path."' and file_name in(".join(',',$file_name_arr).") and uid=$uid and file_status=".\FileStatus::NORMAL." and id!=".$file->id));
            $f = new \FileUtil();
            if($checkSameNameFile)
            {
                if($is_force==1)
                {
                    $this->db->begin();
                    foreach($files as $file)
                    {
                        $new_file_name = $this->getLastFileName($uid,$file->id,$file->file_name,$folder_path);
                        if(!$f->moveFile($this->config->upload_path.$uid.$file->path.$file->file_name,$this->config->upload_path.$uid.$folder_path.$new_file_name))
                        {
                            $this->db->rollback();
                            $this->responseData = array("code"=>\Code::ERROR_DB_WRITE,"msg"=>'文件移动失败[服务器]',"line"=>__LINE__,"data"=>array());
                            break;
                        }
                        $file->path = $folder_path;
                        $file->file_name = $new_file_name;
                        if(!$file->update())
                        {
                            $this->db->rollback();
                            $this->responseData = array("code"=>\Code::ERROR_DB,"msg"=>'文件移动失败',"line"=>__LINE__,"data"=>array());
                            break;
                        }
                    }
                    $this->db->commit();
                }
                else
                {
                    $this->responseData = array("code"=>\Code::ERROR_DB_WRITE,"msg"=>'此目录下已存在同名文件，是否要保存两个文件',"line"=>__LINE__,"data"=>array());
                    break;
                }
            }
            else
            {
                $this->db->begin();
                foreach($files as $file)
                {
                    $new_file_name = $file->file_name;
                    if(!$f->moveFile($this->config->upload_path.$uid.$file->path.$file->file_name,$this->config->upload_path.$uid.$folder_path.$new_file_name,true))
                    {
                        $this->db->rollback();
                        $this->responseData = array("code"=>\Code::ERROR_DB_WRITE,"msg"=>'文件移动失败[服务器]',"line"=>__LINE__,"data"=>array());
                        break;
                    }
                    $file->path = $folder_path;
                    if(!$file->update())
                    {
                        $this->db->rollback();
                        $this->responseData = array("code"=>\Code::ERROR_DB,"msg"=>'文件移动失败',"line"=>__LINE__,"data"=>array());
                        break;
                    }
                }
                $this->db->commit();
            }
        }while(false);
        $this->output();
    }

    /**
     * @brief 移动文件到目录
     * @param file_id   文件ID
     * @param folder_id 文件夹ID  如果是0：代表根目录
     * @param is_force (选填)是否强制重命名 0：否（默认） 1：是
     */
    public function mvFileToFolderAction()
    {
//        $this->params = array('file_id'=>28,'folder_id'=>7);
        do {
            $this->validation->add('file_id', new PresenceOf(array('message'=>'参数缺失:file_id')));
            $this->validation->add('folder_id', new PresenceOf(array('message'=>'参数缺失:folder_id')));
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
            $file_id = $this->params['file_id'];
            $folder_id = (int)$this->params['folder_id'];
            $is_force = isset($this->params['is_force'])?(int)$this->params['is_force']:0;

            $file = Userfile::findFirst(array("id=$file_id and uid=$uid and file_status=".\FileStatus::NORMAL));
            if(!$file)
            {
                $this->responseData = array("code"=>\Code::ERROR,"msg"=>'文件不存在',"line"=>__LINE__,"data"=>array());
                break;
            }
            if($file->file_type==\FileType::FOLDER)
            {
                $this->responseData = array("code"=>\Code::ERROR,"msg"=>'文件夹不支持移动',"line"=>__LINE__,"data"=>array());
                break;
            }
            if($folder_id>0)
            {
                $folder = Userfile::findFirst(array("id=$folder_id and uid=$uid and file_status=".\FileStatus::NORMAL." and file_type=".\FileType::FOLDER));
                if(!$folder)
                {
                    $this->responseData = array("code"=>\Code::ERROR,"msg"=>'文件夹不存在',"line"=>__LINE__,"data"=>array());
                    break;
                }
                $folder_path = $folder->path.$folder->id.'/';
            }
            else
            {
                $folder_path = '/';
            }

            $new_file_name = $file->file_name;

            $sameNameFile = Userfile::findFirst(array("path='".$folder_path."' and file_name='$new_file_name' and uid=$uid and file_status=".\FileStatus::NORMAL." and id!=".$file->id));
            if($sameNameFile)
            {
                if($is_force==1)    //强制重命名
                {
                    $new_file_name = $this->getLastFileName($uid,$file->id,$file->file_name,$folder_path);
                }
                else
                {
                    $this->responseData = array("code"=>\Code::ERROR_DB_WRITE,"msg"=>'此目录下已存在同名文件，是否要保存两个文件',"line"=>__LINE__,"data"=>array());
                    break;
                }
            }
            $this->db->begin();
            $f = new \FileUtil();
            if(!$f->moveFile($this->config->upload_path.$uid.$file->path.$file->file_name,$this->config->upload_path.$uid.$folder_path.$new_file_name,true))
            {
                $this->db->rollback();
                $this->responseData = array("code"=>\Code::ERROR_DB_WRITE,"msg"=>'文件移动失败[服务器]',"line"=>__LINE__,"data"=>array());
                break;
            }
            $file->path = $folder_path;
            $file->file_name = $new_file_name;
            if(!$file->update())
            {
                $this->db->rollback();
                $this->responseData = array("code"=>\Code::ERROR_DB,"msg"=>'文件移动失败',"line"=>__LINE__,"data"=>array());
                break;
            }
            $this->db->commit();
            $this->responseData['data']['name'] = $new_file_name;
        }while(false);
        $this->output();
    }

    /**
     * @brief 【批量删除】批量删除文件&文件夹
     * @param file_ids (数组)
     * @param （可选）trash_type 默认是:1：移动到回收站 2是删除
     */
    public function deleteBatchFileAction()
    {
        do {
            $this->validation->add('file_ids', new PresenceOf(array('message'=>'参数缺失:file_ids')));
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
            $file_ids = $this->params['file_ids'];
            $trash_type = isset($this->params['trash_type'])&&(int)$this->params['trash_type']==2?2:1;
            $files = Userfile::find(array("id in(".join(',',$file_ids).") and uid=$uid and file_status=".\FileStatus::NORMAL));

            $f = new \FileUtil();
            foreach($files as $file)
            {
                if($file->file_type>\FileType::FOLDER)
                {
                    $this->db->begin();
                    $file->file_status=$trash_type;
                    $file->del_type=1;
                    if( !$file->update()){
                        $this->db->rollback();
                        $this->responseData = array("code"=>\Code::ERROR,"msg"=>'删除文件数据失败',"line"=>__LINE__,"data"=>array());
                        break;
                    }
                    $filename = $this->config->upload_path.$uid.$file->path.$file->file_name;
                    $recoverPool = $this->config->upload_recoverpool.$uid.$file->path.$file->file_name;
                    if($trash_type==2)
                    {
                        //删除回收池里的文件超链接
                        if(!$f->unlinkFile($recoverPool))
                        {
                            $this->db->rollback();
                            $this->responseData = array("code"=>\Code::ERROR,"msg"=>'删除文件失败',"line"=>__LINE__,"data"=>array());
                            break;
                        }
                        $sql = "update edu_files set file_count=file_count-1 where id=".$file->file_id;
                        $files= $this->db->query($sql);
                        if(!$files)
                        {
                            $this->db->rollback();
                            $this->responseData = array("code"=>\Code::ERROR,"msg"=>'计数失败',"line"=>__LINE__,"data"=>array());
                            break;
                        }
                    }
                    else
                    {
                        //移动文件到回收站
                        if(!$f->moveFile($filename,$recoverPool,true))
                        {
                            $this->db->rollback();
                            $this->responseData = array("code"=>\Code::ERROR,"msg"=>'移动文件至回收站失败',"line"=>__LINE__,"data"=>array());
                            break;
                        }
                    }
                    $this->db->commit();
                }
            }

        }while(false);
        $this->output();
    }

    /**
     * @brief 删除文件&文件夹
     * @param file_id
     * @param (可选) trash_type 默认是:1：回收站 2是删除
     */
    public function deleteFileAction()
    {
        do {
            $this->validation->add('file_id', new PresenceOf(array('message'=>'参数缺失:path')));
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
            $file_id = $this->params['file_id'];
            $trash_type = isset($this->params['trash_type'])&&(int)$this->params['trash_type']==2?2:1;
            $file = Userfile::findFirst(array("id=$file_id and uid=$uid"));
            if(!$file)
            {
                $this->responseData = array("code"=>\Code::ERROR,"msg"=>'文件不存在',"line"=>__LINE__,"data"=>array());
                break;
            }
            $this->db->begin();
            if($file->file_type==\FileType::FOLDER)
            {
                $child_path = $file->path.$file->id.'/';
                $sql = "select * from edu_user_file where uid=$uid and left(path,".strlen($child_path).") ='".$child_path."'";
                $allFiles = new Userfile();
                $allFiles = new Resultset(null, $allFiles, $allFiles->getReadConnection()->query($sql));
                $allFileIds = array();
                $fileIDs = array();
                foreach($allFiles as $k=>$allFile)
                {
                    array_push($allFileIds,$allFile->id);
                    if($allFile->file_type!=\FileType::FOLDER&&!in_array($allFile->file_id,$fileIDs))
                    {
                        array_push($fileIDs,$allFile->file_id);
                    }
                }
                //删除子文件计数
                if(!empty($fileIDs)&&$trash_type==2)
                {
                    $sql = "update edu_files set file_count=file_count-1 where id in(".join(',',$fileIDs).")";
                    $files= $this->db->query($sql);
                    if(!$files)
                    {
                        $this->db->rollback();
                        $this->responseData = array("code"=>\Code::ERROR,"msg"=>'计数失败',"line"=>__LINE__,"data"=>array());
                        break;
                    }
                }
                //删除文件、文件夹夹子目录
                if(!empty($allFileIds))
                {
                    $sql = "update edu_user_file set file_status=$trash_type,del_type=2 where file_status<".\FileStatus::DELETE." and id in(".join(',',$allFileIds).")";
                    $userChildFile = $this->db->query($sql);
                    if(!$userChildFile)
                    {
                        $this->db->rollback();
                        $this->responseData = array("code"=>\Code::ERROR,"msg"=>'删除文件夹子目录失败',"line"=>__LINE__,"data"=>array());
                        break;
                    }
                }
                $file->file_status=$trash_type;
                $file->del_type=1;
                if( !$file->update()){
                    $this->db->rollback();
                    $this->responseData = array("code"=>\Code::ERROR,"msg"=>'删除文件夹失败',"line"=>__LINE__,"data"=>array());
                    break;
                }
                $recoverPool = $this->config->upload_recoverpool.$uid.$file->path.$file->id;
                $f = new \FileUtil();
                if($trash_type==2)
                {
                    //删除回收池里的文件夹及文件超链接
                    if(!$f->unlinkDir($recoverPool))
                    {
                        $this->db->rollback();
                        $this->responseData = array("code"=>\Code::ERROR,"msg"=>'删除文件夹失败',"line"=>__LINE__,"data"=>array());
                        break;
                    }
                }
                else
                {
                    //移动文件夹到回收池
                    $path = $this->config->upload_path.$uid.$file->path.$file->id;
                    if(!$f->moveDir($path,$recoverPool))
                    {
                        $this->db->rollback();
                        $this->responseData = array("code"=>\Code::ERROR,"msg"=>'移动文件夹至回收站失败',"line"=>__LINE__,"data"=>array());
                        break;
                    }
                }
            }
            else
            {
                $file->file_status=$trash_type;
                $file->del_type=1;
                if( !$file->update()){
                    $this->db->rollback();
                    $this->responseData = array("code"=>\Code::ERROR,"msg"=>'删除文件数据失败',"line"=>__LINE__,"data"=>array());
                    break;
                }
                $filename = $this->config->upload_path.$uid.$file->path.$file->file_name;
                $recoverPool = $this->config->upload_recoverpool.$uid.$file->path.$file->file_name;
                $f = new \FileUtil();
                if($trash_type==2)
                {
                    //删除回收池里的文件超链接
                    if(!$f->unlinkFile($recoverPool))
                    {
                        $this->db->rollback();
                        $this->responseData = array("code"=>\Code::ERROR,"msg"=>'删除文件失败',"line"=>__LINE__,"data"=>array());
                        break;
                    }
                    $sql = "update edu_files set file_count=file_count-1 where id=".$file->file_id;
                    $files= $this->db->query($sql);
                    if(!$files)
                    {
                        $this->db->rollback();
                        $this->responseData = array("code"=>\Code::ERROR,"msg"=>'计数失败',"line"=>__LINE__,"data"=>array());
                        break;
                    }
                }
                else
                {
                    //移动文件到回收站
                    if(!$f->moveFile($filename,$recoverPool))
                    {
                        $this->db->rollback();
                        $this->responseData = array("code"=>\Code::ERROR,"msg"=>'移动文件至回收站失败',"line"=>__LINE__,"data"=>array());
                        break;
                    }
                }
            }
            $this->db->commit();
        }while(false);
        $this->output();
    }

    /**
     * @brief 发布文件
     * @param file_id
     * @param subject_id
     * @param knowledge_point
     * @param language
     * @param application_type
     * @param desc
     */
    public function pushFileToPublicAction()
    {
//        $this->params = array('file_id'=>31,'subject_id'=>12,'knowledge_point'=>'测试','language'=>'中文','desc'=>'测试测试测试');
        do {
            $this->validation->add('file_id', new PresenceOf(array('message'=>'参数缺失:file_id')));
            $this->validation->add('subject_id', new PresenceOf(array('message'=>'参数缺失:subject_id')));
            $this->validation->add('knowledge_point', new PresenceOf(array('message'=>'参数缺失:knowledge_point')));
            $this->validation->add('language', new PresenceOf(array('message'=>'参数缺失:language')));
            $this->validation->add('application_type', new PresenceOf(array('message'=>'参数缺失:application_type')));
            $this->validation->add('desc', new PresenceOf(array('message'=>'参数缺失:desc')));
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
            $file_id = (int)$this->params['file_id'];
            $subject_id = (int)$this->params['subject_id'];
            $knowledge_point = $this->params['knowledge_point'];
            $application_type = (int)$this->params['application_type'];
            $language = (int)$this->params['language'];
            $desc = $this->params['desc'];
            $file = Userfile::findFirst(array("id=$file_id and uid=$uid and file_status=".\FileStatus::NORMAL));
            if(!$file)
            {
                $this->responseData = array("code"=>\Code::ERROR,"msg"=>'文件不存在',"line"=>__LINE__,"data"=>array());
                break;
            }
            if($file->file_type==\FileType::FOLDER)
            {
                $this->responseData = array("code"=>\Code::ERROR,"msg"=>'文件夹不能发布',"line"=>__LINE__,"data"=>array());
                break;
            }
            $userFilePush = Userfilepush::findFirst(array("user_file_id=$file_id and uid=$uid"));
            if($userFilePush&&$userFilePush->status==0)
            {
                $this->responseData = array("code"=>\Code::ERROR,"msg"=>'该文件已发布,请等待审核',"line"=>__LINE__,"data"=>array());
                break;
            }
            $this->db->begin();
            $date = date('Y-m-d');
            $userFilePush = new Userfilepush();
            $userFilePush->user_file_id = $file_id;
            $userFilePush->uid = $uid;
            $userFilePush->file_type = $file->file_type;
            $userFilePush->subject_id = $subject_id;
            $userFilePush->push_file_name = $file->file_name;
            $userFilePush->push_date_folder = $date;
            $userFilePush->addtime = $userFilePush->setAddtime();
            if(!$userFilePush->create())
            {
                $this->db->rollback();
                $this->responseData = array("code"=>\Code::ERROR,"msg"=>'文件发布失败',"line"=>__LINE__,"data"=>array());
                break;
            }
            $userFileInfo = new Userfileinfo();
            $userFileInfo->user_file_id = $file_id;
            $userFileInfo->subject_id = $subject_id;
            $userFileInfo->knowledge_point = $knowledge_point;
            $userFileInfo->language = $language;
            $userFileInfo->application_type = $application_type;
            $userFileInfo->desc = $desc;
            $userFileInfo->addtime = $userFileInfo->setAddtime();
            if(!$userFileInfo->create())
            {
                $this->db->rollback();
                $this->responseData = array("code"=>\Code::ERROR,"msg"=>'文件信息发布失败',"line"=>__LINE__,"data"=>array());
                break;
            }
            $userInfo = Userinfo::findFirst(array("uid=$uid"));
            $userInfo->push_file_count = $userInfo->push_file_count+1;
            if(!$userInfo->update())
            {
                $this->db->rollback();
                $this->responseData = array("code"=>\Code::ERROR_DB,"msg"=>"文件信息发布失败2","line"=>__LINE__,"data"=>array());
                break;
            }
            $this->db->commit();
            //判断分享池目录是否存在
            $publicPoolDate = $this->config->upload_publicpool.$uid.'/'.$date;
            if(!is_dir($publicPoolDate))
            {
                mkdir($publicPoolDate,0755,true);
            }
            if(!file_exists($publicPoolDate.'/'.$file->file_name))
            {
                symlink(readlink($this->config->upload_path.$uid.$file->path.$file->file_name),$publicPoolDate.'/'.$file->file_name);
            }
        }while(false);
        $this->output();
    }

    /**
     * @brief 收藏文件
     * @param file_id
     */
    public function collectFileAction()
    {
//        $this->params = array('file_id'=>45);
        do {
            $this->validation->add('file_id', new PresenceOf(array('message'=>'参数缺失:path')));
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
            $file_id = $this->params['file_id'];
            $file = Userfile::findFirst(array("id=$file_id"));
            if($file->file_type == \FileType::FOLDER)
            {
                $this->responseData = array("code"=>\Code::ERROR,"msg"=>'文件夹无法收藏',"line"=>__LINE__,"data"=>array());
                break;
            }
            if($file->uid == $uid)
            {
                $this->responseData = array("code"=>\Code::ERROR,"msg"=>'无法收藏自己的文件',"line"=>__LINE__,"data"=>array());
                break;
            }
            $userFileCollect = Userfilecollect::findFirst("uid=$uid and user_file_id=$file_id");
            if($userFileCollect)
            {
                $this->responseData = array("code"=>\Code::ERROR,"msg"=>'您已收藏过该文件',"line"=>__LINE__,"data"=>array());
                break;
            }
            $file_push = Userfilepush::findFirst(array("user_file_id=$file_id"));
            if(!$file_push)
            {
                $this->responseData = array("code"=>\Code::ERROR,"msg"=>'文件不存在',"line"=>__LINE__,"data"=>array());
                break;
            }
            //获取个人传给我的所有文件
            $toUserFiles = Touserfile::find(array("to_uid = $uid"));
            $toUserFileIds = array();
            foreach($toUserFiles as $toUserFile)
            {
                array_push($toUserFileIds,$toUserFile->user_file_id);
            }
            if(!in_array($file_id,$toUserFileIds))
            {
                //获取我的所有群组的文件
                $groupUsers = Groupuser::find(array("uid=$uid and user_status=1"));
                $myGroupIds = array();  //我的所有群组ID
                foreach($groupUsers as $groupUser)
                {
                    array_push($myGroupIds,$groupUser->gid);
                }
                //获取我的群组内的所有文件塞入数组
                $group_file_ids = array();
                if(!empty($myGroupIds))
                {
                    $groupFiles = Groupfile::find(array("gid in (".join(',',$myGroupIds).")"));
                    foreach($groupFiles as $groupFile)
                    {
                        array_push($group_file_ids,$groupFile->user_file_id);
                    }
                }
                if(!in_array($file_id,$group_file_ids))
                {
                    $filePush = Userfilepush::findFirst(array("user_file_id=$file_id"));
                    if(!$filePush)
                    {
                        $this->responseData = array("code"=>\Code::ERROR,"msg"=>'文件无法收藏',"line"=>__LINE__,"data"=>array());
                        break;
                    }
                }
            }
            $userFileCollect = new Userfilecollect();
            $userFileCollect->uid = $uid;
            $userFileCollect->user_file_id = $file_id;
            $userFileCollect->collect_date_folder = $file_push->push_date_folder;
            $userFileCollect->collect_file_name = $file_push->push_file_name;
            $userFileCollect->addtime = $userFileCollect->setAddtime();
            if(!$userFileCollect->create())
            {
                $this->responseData = array("code"=>\Code::ERROR_DB,"msg"=>"收藏失败","line"=>__LINE__,"data"=>array());
                break;
            }
        }while(false);
        $this->output();
    }

    /**
     * @brief 取消收藏文件
     * @param file_id
     */
    public function delCollectFileAction(){
//        $this->params = array('file_id'=>45);
        do {
            $this->validation->add('file_id', new PresenceOf(array('message'=>'参数缺失:path')));
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
            $file_id = $this->params['file_id'];
            $userFileCollect = Userfilecollect::findFirst("uid=$uid and user_file_id=$file_id");
            if(!$userFileCollect)
            {
                $this->responseData = array("code"=>\Code::ERROR,"msg"=>'文件未收藏',"line"=>__LINE__,"data"=>array());
                break;
            }
            if(!$userFileCollect->delete())
            {
                $this->responseData = array("code"=>\Code::ERROR_DB,"msg"=>"取消收藏失败","line"=>__LINE__,"data"=>array());
                break;
            }
        }while(false);
        $this->output();
    }

    /**
     * @brief 【批量下载】批量下载文件
     * @param file_ids（字符串,逗号拼接） 文件id  例：1,2,3...
     * @param auth_token 加密字符串  md5(file_ids+key) key:P$&*WIND758U
     * @param timestamp 时间戳 1450000000
     */
    public function downloadFilesAction()
    {
        do {
            $this->validation->add('file_ids', new PresenceOf(array('message'=>'参数缺失:file_ids')));
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
            if(!$this->checkUserLogin())
            {
                $this->response->redirect("login/login");
                break;
            }
            $uid = $this->uid;
            $file_ids = $this->request->get('file_ids');
            $auth_token = $this->request->get('auth_token');
            $timestamp = $this->request->get('timestamp');
            $token = md5($file_ids.'P$&*WIND758U'.$timestamp);
            if($token!=$auth_token)
            {
                $this->responseData = array("code"=>\Code::ERROR,"msg"=>"无效的下载","line"=>__LINE__,"data"=>array());
                break;
            }
            $file_id_arr = explode(',',$file_ids);
            $toDownloadFiles = array();
            //获取分享文件塞入数组
            $sharefile = Userfileshare::findFirst(array("status=0 and user_file_id in (".join(',',$file_id_arr).")"));
            if($sharefile)
            {
                array_push($toDownloadFiles,$sharefile->user_file_id);
            }
            //获取自己文件塞入数组
            $files = Userfile::find(array("uid=$uid and file_status=".\FileStatus::NORMAL." and id in (".join(',',$file_id_arr).")"));
            foreach($files as $file)
            {
                if($file->uid == $uid)
                {
                    array_push($toDownloadFiles,$file->id);
                }
            }
            //获取公开文件塞入数组
            $filePushs = Userfilepush::find(array("user_file_id in (".join(',',$file_id_arr).")"));
            foreach($filePushs as $filePush)
            {
                if(!in_array($filePush->user_file_id,$toDownloadFiles))
                {
                    array_push($toDownloadFiles,$filePush->user_file_id);
                }
            }
            $groupUsers = Groupuser::find(array("uid=$uid and user_status=1"));
            $myGroupIds = array();  //我的所有群组ID
            foreach($groupUsers as $groupUser)
            {
                array_push($myGroupIds,$groupUser->gid);
            }
            //获取我的群组内的所有文件塞入数组
            if(!empty($myGroupIds))
            {
                $groupFiles = Groupfile::find(array("gid in (".join(',',$myGroupIds).") and user_file_id in(".join(',',$file_id_arr).")"));
                foreach($groupFiles as $groupFile)
                {
                    if(!in_array($groupFile->user_file_id,$toDownloadFiles))
                    {
                        array_push($toDownloadFiles,$groupFile->user_file_id);
                    }
                }
            }
            if(!empty($toDownloadFiles))
            {
                $addSql = "INSERT INTO edu_user_file_download(user_file_id,uid) VALUES";
                $files = Userfile::find(array("id in (".join(',',$toDownloadFiles).") and file_status=".\FileStatus::NORMAL));
                $filesArr = array();
                foreach($files as $file)
                {
                    $userFile = Userfile::findFirst(array("id=".$file->id));
                    $userFile->download_count = $userFile->download_count+1;
                    $userFile->update();
                    $file_name = $file->file_name;
                    array_push($filesArr,$this->config->upload_path.$file->uid.$file->path.$file_name);
                    $addSql .="(".$file->id.",".$uid."),";
                }
                $batch = false;
                if(count($filesArr)>1)
                {
                    if(!is_dir($this->config->upload_batchfiletmp))
                    {
                        mkdir($this->config->upload_batchfiletmp,0755,true);
                    }
                    $file_name = '【批量下载】'.basename($file_name,strrchr($file_name, '.')).'等.zip';
                    $file_path = $this->config->upload_batchfiletmp.$file_name;
                    $this->createZip($filesArr,$file_path);
                    $batch = true;
                }
                else
                {
                    $file_path = $filesArr[0];
                }
                $addSql = substr($addSql,0,strlen($addSql)-1);
                $add = $this->db->query($addSql);
                if(!$add)
                {
                    $this->responseData = array("code"=>\Code::ERROR_DB,"msg"=>'下载异常',"line"=>__LINE__,"data"=>array());
                    break;
                }
                $fp=fopen($file_path,"r");
                $file_size=filesize($file_path);
                //下载文件需要用到的头
                Header("Content-type: application/octet-stream");
                Header("Accept-Ranges: bytes");
                Header("Accept-Length:".$file_size);
                Header("Content-Disposition: attachment; filename=".$file_name);
                $buffer=1024;
                $file_count=0;
                //向浏览器返回数据
                while(!feof($fp) && $file_count<$file_size){
                    $file_con=fread($fp,$buffer);
                    $file_count+=$buffer;
                    echo $file_con;
                }
                fclose($fp);
                if($batch)
                {
                    @unlink($file_path);
                }
                exit;
            }
        }while(false);
        $this->output();
    }

    /**
     * @brief 文件详情
     * @param file_id
     */
    public function getFileDetailAction()
    {
//        $this->params = array('file_id'=>180);
        do {
            $this->validation->add('file_id', new PresenceOf(array('message'=>'参数缺失:file_id')));
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
            $file_id = $this->params['file_id'];
            $file = Userfile::findFirst(array("id = $file_id"));
            if(!$file)
            {
                $this->responseData = array("code"=>\Code::ERROR_DB,"msg"=>'文件不存在',"line"=>__LINE__,"data"=>array());
                break;
            }
            $filePush = Userfilepush::findFirst(array("user_file_id=$file_id"));
            if(!$filePush)
            {
                $this->responseData = array("code"=>\Code::ERROR_DB,"msg"=>'文件未发布',"line"=>__LINE__,"data"=>array());
                break;
            }
            $fileInfo = Userfileinfo::findFirst(array("user_file_id=$file_id"));
            $fileDetail = array_merge($file->toArray(),$fileInfo->toArray());

            $applicationTypeName = '';
            foreach(\FileApplicationType::$ApplicationType as $v)
            {
                if($fileDetail['application_type']==$v['id'])
                {
                    $applicationTypeName = $v['name'];
                    break;
                }
            }
            $fileDetail['file_name'] = $filePush->push_file_name;
            //应用类型名称
            $fileDetail['application_type_name'] = $applicationTypeName;
            $languageName = '';
            foreach(\FileLanguage::$Language as $v)
            {
                if($fileDetail['language']==$v['id'])
                {
                    $languageName = $v['name'];
                    break;
                }
            }
            //学科名称
            $fileDetail['language_name'] = $languageName;
            $subject = Subject::findFirst(array("id=".$fileDetail['subject_id']));
            $fatherSubject = $subject->getFatherSubject();
            //学科名称（父级学科-子级学科）
            $fileDetail['subject_name'] = $fatherSubject->subject_name.'-'.$subject->subject_name;
            //文件大小格式
            $fileDetail['file_size'] = $this->fileSizeConv($fileDetail['file_size']);

            $fileDetail['file_comment'] = array();
            //文件评价列表
            $userFileComments = Userfilecomment::find(array("user_file_id=$file_id"));
            foreach($userFileComments as $userFileComment){
                $user_file_comment_info = $userFileComment->toArray();
                $user_file_comment_info['date'] = $this->dateConv($user_file_comment_info['create_time']);
                if( $userFileComment->ref_uid == 0 ){
                    $user_file_comment_info['refUserName'] = "";
                    $user_file_comment_info['refHeadpic'] = array();
                }
                else
                {
                    $refUserInfo = $userFileComment->getRefUserInfo();
                    $user_file_comment_info['refUserName'] = $refUserInfo->nick_name;
                    $user_file_comment_info['refHeadpic'] = $refUserInfo->headpic;
                }
                $userInfo = $userFileComment->getUserInfo();
                $user_file_comment_info['userName'] = $userInfo->nick_name;
                $user_file_comment_info['headpic'] = $userInfo->headpic;
                if($userFileComment->ref_id == 0 ){
                    $fileDetail['file_comment'][$userFileComment->id][] = $user_file_comment_info;
                }
                else
                {
                    $fileDetail['file_comment'][$userFileComment->ref_id][] = $user_file_comment_info;
                }
            }
            $this->responseData['data']['file'] = $fileDetail;
        }while(false);
        $this->output();
    }

    /**
     * @brief 批量发布文件
     * @param file_ids
     * @param subject_id
     * @param knowledge_point
     * @param language
     * @param application_type
     * @param desc
     */
    public function BatchPushFileToPublicAction()
    {
//        $this->params = array('file_id'=>31,'subject_id'=>12,'knowledge_point'=>'测试','language'=>'中文','desc'=>'测试测试测试');
        do {
            $this->validation->add('file_ids', new PresenceOf(array('message'=>'参数缺失:file_ids')));
            $this->validation->add('subject_id', new PresenceOf(array('message'=>'参数缺失:subject_id')));
            $this->validation->add('knowledge_point', new PresenceOf(array('message'=>'参数缺失:knowledge_point')));
            $this->validation->add('language', new PresenceOf(array('message'=>'参数缺失:language')));
            $this->validation->add('application_type', new PresenceOf(array('message'=>'参数缺失:application_type')));
            $this->validation->add('desc', new PresenceOf(array('message'=>'参数缺失:desc')));
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
            $file_ids = $this->params['file_ids'];
            $subject_id = (int)$this->params['subject_id'];
            $knowledge_point = $this->params['knowledge_point'];
            $application_type = (int)$this->params['application_type'];
            $language = (int)$this->params['language'];
            $desc = $this->params['desc'];

            if(!empty($file_ids)&&!is_array($file_ids))
            {
                $this->responseData = array("code"=>\Code::ERROR_PARAMS,"msg"=>'file_ids必须为数据格式',"line"=>__LINE__,"data"=>array());
                break;
            }
            $files = Userfile::find(array("id in (".join(',',$file_ids).") and file_type>".\FileType::FOLDER." and uid=$uid and file_status=".\FileStatus::NORMAL));
            $fileIdArr = array();
            foreach($files as $file)
            {
                array_push($fileIdArr,$file->id);
            }
            if(!empty($fileIdArr))
            {
                $userFilePushs = Userfilepush::find(array("user_file_id in(".join(',',$fileIdArr).") and uid=$uid and status=0"));
                foreach($userFilePushs as $userFilePush)
                {
                    unset($fileIdArr[array_search($userFilePush->user_file_id,$fileIdArr)]);
                }
            }
            if(!empty($fileIdArr))
            {
                $files = Userfile::find(array("id in (".join(',',$fileIdArr).")"));
                foreach($files as $file)
                {
                    $date = date('Y-m-d');
                    $userFilePush = new Userfilepush();
                    $userFilePush->user_file_id = $file->id;
                    $userFilePush->uid = $uid;
                    $userFilePush->file_type = $file->file_type;
                    $userFilePush->subject_id = $subject_id;
                    $userFilePush->push_file_name = $file->file_name;
                    $userFilePush->push_date_folder = $date;
                    $userFilePush->addtime = $userFilePush->setAddtime();
                    if(!$userFilePush->create())
                    {
                        $this->responseData = array("code"=>\Code::ERROR,"msg"=>'文件发布失败',"line"=>__LINE__,"data"=>array());
                        break;
                    }
                    $userFileInfo = new Userfileinfo();
                    $userFileInfo->user_file_id = $file->id;
                    $userFileInfo->subject_id = $subject_id;
                    $userFileInfo->knowledge_point = $knowledge_point;
                    $userFileInfo->language = $language;
                    $userFileInfo->application_type = $application_type;
                    $userFileInfo->desc = $desc;
                    $userFileInfo->addtime = $userFileInfo->setAddtime();
                    if(!$userFileInfo->create())
                    {
                        $this->responseData = array("code"=>\Code::ERROR,"msg"=>'文件信息发布失败',"line"=>__LINE__,"data"=>array());
                        break;
                    }
                    $userInfo = Userinfo::findFirst(array("uid=$uid"));
                    $userInfo->push_file_count = $userInfo->push_file_count+1;
                    if(!$userInfo->update())
                    {
                        $this->responseData = array("code"=>\Code::ERROR_DB,"msg"=>"文件信息发布失败2","line"=>__LINE__,"data"=>array());
                        break;
                    }
                    //判断分享池目录是否存在
                    $publicPoolDate = $this->config->upload_publicpool.$uid.'/'.$date;
                    if(!is_dir($publicPoolDate))
                    {
                        mkdir($publicPoolDate,0755,true);
                    }
                    if(!file_exists($publicPoolDate.'/'.$file->file_name))
                    {
                        symlink(readlink($this->config->upload_path.$uid.$file->path.$file->file_name),$publicPoolDate.'/'.$file->file_name);
                    }
                }
            }
            else
            {
                $this->responseData = array("code"=>\Code::ERROR_PARAMS,"msg"=>'文件已发布',"line"=>__LINE__,"data"=>array());
                break;
            }
        }while(false);
        $this->output();
    }

    public function BatchPushFileToPublic2Action(){
//        $this->params = array('file_ids'=>array(114,131,116),'folder'=>'/','excel_path'=>'upload/excel/model.xls');
        do {
            $this->validation->add('file_ids', new PresenceOf(array('message'=>'参数缺失:file_ids')));
            $this->validation->add('folder', new PresenceOf(array('message'=>'参数缺失:file_ids')));
            $this->validation->add('excel_path', new PresenceOf(array('message'=>'参数缺失:file_path')));
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
            $file_ids = $this->params['file_ids'];
            $excel_path = $this->params['excel_path'];
            $folder = $this->params['folder'];
            $files = Userfile::find(array("id in(".join(',',$file_ids).") and uid=$uid and file_type>".\FileType::FOLDER." and file_status=".\FileStatus::NORMAL));
            $file_ids = array();
            $file_names = array();
            foreach($files as $file)
            {
                array_push($file_ids,$file->id);
                array_push($file_names,$file->file_name);
            }
            if(!empty($file_ids))
            {
                $userFilePushs = Userfilepush::find(array("user_file_id in(".join(',',$file_ids).") and uid=$uid"));
                $userFilePush_ids = array();
                foreach($userFilePushs as $userFilePush)
                {
                    array_push($userFilePush_ids,$userFilePush->user_file_id);
                }
                $last_file_ids = array_diff($file_ids,$userFilePush_ids);
                if(!empty($last_file_ids))
                {
                    $file_path = '/home/debian/www/upload/'.$excel_path;
                    if (!file_exists($file_path))
                    {
                        $this->responseData = array("code"=>\Code::ERROR,"msg"=>'excel文件不存在',"line"=>__LINE__,"data"=>array());
                        break;
                    }
                    $reader = \PHPExcel_IOFactory::createReader('Excel5'); //设置以Excel5格式(Excel97-2003工作簿)
                    $PHPExcel = $reader->load($file_path); // 载入excel文件
                    $sheet = $PHPExcel->getSheet(0); // 读取第一個工作表
                    $highestRow = $sheet->getHighestRow(); // 取得总行数
                    $highestColumm = $sheet->getHighestColumn(); // 取得总列数
                    /** 循环读取每个单元格的数据 */
                    $data_arr = array();
                    for ($row = 2; $row <= $highestRow; $row++){//行数是以第1行开始
                        for ($column = 'A'; $column <= $highestColumm; $column++) {//列数是以A列开始
                            $dataset[] = $sheet->getCell($column.$row)->getValue();
                            if(!empty(trim($sheet->getCell($column.$row)->getValue())))
                            {
                                $data_arr[$row][] = $sheet->getCell($column.$row)->getValue();
                            }
                        }
                    }
                    if(!empty($data_arr))
                    {
                        $last_file_name = array();
                        $file_push_info = array();
                        foreach($data_arr as $k=>$v)
                        {
                            if(in_array($v[0],$file_names))
                            {
                                array_push($last_file_name,"'".$v[0]."'");
                                $file_push_info[$v[0]] = $v;
                            }
                        }
                        if(!empty($last_file_name))
                        {
                            $fs = Userfile::find(array("uid=$uid and path='$folder' and file_name in(".join(',',$last_file_name).")"));
                            $this->db->begin();
                            $date = date('Y-m-d');
                            foreach($fs as $f)
                            {
                                $bool = true;
                                $ApplicationTypeId = 0;
                                foreach(\FileApplicationType::$ApplicationType as $v)
                                {
                                    if($file_push_info[$f->file_name][2]==$v['name'])
                                    {
                                        $ApplicationTypeId = $v['id'];
                                        break;
                                    }
                                }
                                $languageId = 0;
                                foreach(\FileLanguage::$Language as $v)
                                {
                                    if($file_push_info[$f->file_name][4]==$v['name'])
                                    {
                                        $languageId = $v['id'];
                                        break;
                                    }
                                }
                                $child = explode('-',$file_push_info[$f->file_name][1]);
                                $subject = Subject::findFirst(array("father_id>0 and subject_name='".$child[1]."'"));
                                $subjectId = 0;
                                if($subject)
                                {
                                    $subjectId = $subject->id;
                                }
                                $userFileInfo = new Userfileinfo();
                                $userFileInfo->user_file_id = $f->id;
                                $userFileInfo->subject_id = $subjectId;
                                $userFileInfo->application_type = $ApplicationTypeId;
                                $userFileInfo->knowledge_point = !empty($file_push_info[$f->file_name][3])?$file_push_info[$f->file_name][3]:'';
                                $userFileInfo->language = $languageId;
                                $userFileInfo->desc = !empty($file_push_info[$f->file_name][5])?$file_push_info[$f->file_name][5]:'';
                                $userFileInfo->addtime = $userFileInfo->setAddtime();
                                if(!$userFileInfo->create())
                                {
                                    $this->db->rollback();
                                    $bool = false;
                                    $this->responseData = array("code"=>\Code::ERROR,"msg"=>'文件信息发布失败',"line"=>__LINE__,"data"=>array());
                                    break;
                                }
                                $userFilePush = new Userfilepush();
                                $userFilePush->user_file_id = $f->id;
                                $userFilePush->uid = $uid;
                                $userFilePush->subject_id = $subjectId;
                                $userFilePush->file_type = $f->file_type;
                                $userFilePush->push_file_name = $f->file_name;
                                $userFilePush->push_date_folder = $date;
                                $userFilePush->addtime = $userFilePush->setAddtime();
                                if(!$userFilePush->create())
                                {
                                    $this->db->rollback();
                                    $bool = false;
                                    $this->responseData = array("code"=>\Code::ERROR,"msg"=>'文件发布失败',"line"=>__LINE__,"data"=>array());
                                    break;
                                }
                                //判断分享池目录是否存在
                                if($bool)
                                {
                                    $publicPoolDate = $this->config->upload_publicpool.$uid.'/'.$date;
                                    if(!is_dir($publicPoolDate))
                                    {
                                        mkdir($publicPoolDate,0755,true);
                                    }
                                    if(!file_exists($publicPoolDate.'/'.$f->file_name))
                                    {
                                        symlink(readlink($this->config->upload_path.$uid.$f->path.$f->file_name),$publicPoolDate.'/'.$f->file_name);
                                    }
                                }
                            }
                            $this->db->commit();
                        }
                        else
                        {
                            $this->responseData = array("code"=>\Code::ERROR,"msg"=>'数据错误，请重新上传',"line"=>__LINE__,"data"=>array());
                            break;
                        }
                    }
                    else
                    {
                        $this->responseData = array("code"=>\Code::ERROR,"msg"=>'excel内容不能为空',"line"=>__LINE__,"data"=>array());
                        break;
                    }
                }
            }
            else
            {
                $this->responseData = array("code"=>\Code::ERROR,"msg"=>'无有效的文件可供发布',"line"=>__LINE__,"data"=>array());
                break;
            }
        }while(false);
        $this->output();
    }

    /**
     * @brief 文档文件预览
     * @param file_id
     */
    public function filePreviewAction($file_id)
    {
        $this->params = array('file_id'=>$file_id);
        do {
            $this->validation->add('file_id', new PresenceOf(array('message'=>'参数缺失:file_id')));
            $messages = $this->validation->validate($this->params);
            if (count($messages)) {
                foreach ($messages as $message) {
                    array_push($this->errors, strval($message));
                }
                echo join(';', $this->errors);
                break;
            }
            $uid = 0;
            if($this->checkUserLogin())
            {
                $uid = $this->uid;
            }
            $file_id = $this->params['file_id'];
            $file = Userfile::findFirst(array("id=$file_id and file_type=".\FileType::DOC));
            if(!$file)
            {
                $this->responseData = array("code"=>\Code::ERROR,"msg"=>'文件不存在',"line"=>__LINE__,"data"=>array());
                break;
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
            //判断预览池目录是否存在
            $previewPoolPath = $this->config->upload_previewpool.$file->uid.$file->path;
            if(!is_dir($previewPoolPath))
            {
                mkdir($previewPoolPath,0755,true);
            }
            $filePath = $this->config->upload_previewpool.$file->uid.'/'.$file->id.'/List.txt';
            if(!file_exists($filePath))
            {
                $this->responseData = array("code"=>\Code::ERROR,"msg"=>'文件正在转换中，请稍后刷新',"line"=>__LINE__,"data"=>array());
                break;
            }
            if($fp=fopen($filePath,"a+"))
            {
                $conn=fread($fp,filesize($filePath));
                $images = explode('|',strstr($conn,"1.jpg"));
                $arr = array();
                foreach($images as $image)
                {
                    $basename = strstr($image,".jpg",true);
                    array_push($arr,'/api/source/getPreviewImage/'.$file->id.'/'.$basename);
                }
                $this->responseData = array("code" => 0, "msg" => "", "line" => __LINE__);
                $this->responseData['data']['total'] = count($images);
                $this->responseData['data']['images'] = $arr;
            }
            else
            {
                $this->responseData = array("code"=>\Code::ERROR_DB,"msg"=>'文件打不开',"line"=>__LINE__,"data"=>array());
                break;
            }
        }while(false);
        $this->output();
    }

    /**
     * @brief 文档文件预览（旧版）
     * @param file_id
     */
    public function filePreview2Action($file_id)
    {
        $this->params = array('file_id'=>$file_id);
        do {
            $this->validation->add('file_id', new PresenceOf(array('message'=>'参数缺失:file_id')));
            $messages = $this->validation->validate($this->params);
            if (count($messages)) {
                foreach ($messages as $message) {
                    array_push($this->errors, strval($message));
                }
                echo join(';', $this->errors);
                break;
            }
            $uid = 0;
            if($this->checkUserLogin())
            {
                $uid = $this->uid;
            }
            $file_id = $this->params['file_id'];
            $file = Userfile::findFirst(array("id=$file_id and file_type=".\FileType::DOC));
            if(!$file)
            {
                echo '文件不存在';
                break;
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
                                echo '无权限预览文件';
                                break;
                            }
                        }
                        else
                        {
                            echo '无权限预览文件';
                            break;
                        }
                    }
                }
            }
            //判断预览池目录是否存在
            $previewPoolPath = $this->config->upload_previewpool.$file->uid.$file->path;
            if(!is_dir($previewPoolPath))
            {
                mkdir($previewPoolPath,0755,true);
            }
            $previewFile = $previewPoolPath.$file->file_md5;
            //从文件池里面找文件
            $filePool = Files::findFirst(array("id=".$file->file_id));

            $filePath = $this->config->upload_filepool.$filePool->path.'/'.$filePool->md5;
            $ext = strrchr($file->file_name, '.');
            if(!file_exists($previewFile.'.pdf'))
            {
                if($ext=='.pdf')
                {
                    symlink($filePath,$previewFile.'.pdf');
                }
                else
                {
                    exec('unoconv -vvv -s 127.0.0.1 -p 8100 -f pdf -o '.$previewFile.'.pdf '.$filePath);
                }
            }
            $last_modified_time = filemtime($previewFile.'.pdf');
            $etag = md5_file($previewFile.'.pdf');
            $size = filesize($previewFile.'.pdf');
            $this->response->setHeader("Content-Type", "application/pdf");
            $this->response->setHeader("Content-Length", $size);
            $this->response->setHeader("Last-Modified", gmdate("D, d M Y H:i:s", $last_modified_time)." GMT");
            $this->response->setHeader("Etag:", $etag);
            if (@strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']) == $last_modified_time ||
                @trim($_SERVER['HTTP_IF_NONE_MATCH']) == $etag) {
                header("HTTP/1.1 304 Not Modified");
                exit;
            }
            $content = readfile($previewFile.'.pdf');
            $this->response->setContent($content);
        }while(false);
//        $this->output();
    }

    /**
     * @brief 评价文件
     * @param file_id 文件ID
     * @param ref_uid 回复某个人的ID,非回复则为0
     * @param ref_id 回复的评论ID(请注意与lesson_id的区别,每个课程有个ID,每个评论也有个ID,这个是评论的ID),非回复则为0
     * @param content 评论的内容
     */
    public function commentUserFileAction()
    {
//        $this->params = array('file_id'=>152,'ref_uid'=>0,'ref_id'=>0,'content'=>'这个文件好给力\(^o^)/~');
        do {
            $this->validation->add('file_id', new PresenceOf(array('message'=>'参数缺失:file_id')));
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
            $file_id = (int)$this->params['file_id'];
            $ref_uid = (int)$this->params['ref_uid'];
            $ref_id = (int)$this->params['ref_id'];
            $content = $this->params['content'];
            $filePush = Userfilepush::count(array("user_file_id=$file_id"));
            if($filePush<=0)
            {
                $this->responseData = array("code"=>\Code::ERROR_PARAMS,"msg"=>'文件未发布，无法评论',"line"=>__LINE__,"data"=>array());
                break;
            }
            $userFileComment = new Userfilecomment();
            $userFileComment->uid = $uid;
            $userFileComment->user_file_id = $file_id;
            $userFileComment->content = $content;
            $userFileComment->ref_id = $ref_id;
            $userFileComment->ref_uid = $ref_uid;
            $userFileComment->create_time = $userFileComment->setCreate_time();
            if(!$userFileComment->create())
            {
                $this->responseData = array("code"=>\Code::ERROR_DB,"msg"=>'评价失败',"line"=>__LINE__,"data"=>array());
                break;
            }
        }while(false);
        $this->output();
    }

    /**
     * @brief 下载excel文件
     * @param file_ids（字符串,逗号拼接） 文件id  例：1,2,3...
     * @param auth_token 加密字符串  md5(file_ids+key) key:IND&*W75P$8U
     * @param timestamp 时间戳 1450000000
     */
    public function downloadExcelAction()
    {
        do {
            $this->validation->add('file_ids', new PresenceOf(array('message'=>'参数缺失:file_ids')));
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
            if(!$this->checkUserLogin())
            {
                break;
            }
            $uid = $this->uid;
            $file_ids = $this->request->get('file_ids');
            $auth_token = $this->request->get('auth_token');
            $timestamp = $this->request->get('timestamp');
            $token = md5($file_ids.'IND&*W75P$8U'.$timestamp);
            if($token!=$auth_token)
            {
                $this->responseData = array("code"=>\Code::ERROR,"msg"=>"无效的下载","line"=>__LINE__,"data"=>array());
                break;
            }
            $file_id_arr = explode(',',$file_ids);
            $toDownloadFiles = array();
            //获取自己文件塞入数组
            $files = Userfile::find(array("uid=$uid and file_status=".\FileStatus::NORMAL." and id in (".join(',',$file_id_arr).")"));
            foreach($files as $file)
            {
                if($file->uid == $uid)
                {
                    $toDownloadFiles[] = $file->file_name;
                }
            }
            $file_path = APP_PATH.'/public/excel/model.xls';
            $PHPReader = new \PHPExcel_Reader_Excel2007();
            if(!$PHPReader->canRead($file_path)){
                $PHPReader = new \PHPExcel_Reader_Excel5();
                if(!$PHPReader->canRead($file_path)){
                    $this->responseData = array("code"=>\Code::ERROR,"msg"=>"无法识别的文件","line"=>__LINE__,"data"=>array());
                    break;
                }
            }
            //读取Excel
            $PHPExcel = $PHPReader->load($file_path);
            //读取工作表1
            $currentSheet = $PHPExcel->getSheet(0);

            foreach($toDownloadFiles as $k=>$file_name)
            {
                $currentSheet->setCellValue('A'.($k+2),$file_name);
            }
            $outputFileName = $uid.'_'.time().'.xls';
            //实例化Excel写入类
            $PHPWriter = new \PHPExcel_Writer_Excel2007($PHPExcel);
            ob_start();
            header("Content-Type: application/force-download");
            header("Content-Type: application/octet-stream");
            header("Content-Type: application/download");
            header('Content-Disposition:attachment;filename="' .$outputFileName. '"');//输出模板名称
            header("Content-Transfer-Encoding: binary");
            header("Last-Modified:".gmdate("D, d M Y H:i:s")." GMT");
            header('Pragma: public');
            header('Expires: 30');
            header('Cache-Control: public');
            $PHPWriter->save('php://output');
            exit;
        }while(false);
        $this->output();
    }

    /**
     * @brief 设置/取消空间可见
     * @param file_id
     * @param visible 1：空间可见 0：取消空间可见
     */
    public function visibleAction()
    {
//        $this->params = array('file_id'=>300,'visible'=>1);
        do {
            $this->validation->add('file_id', new PresenceOf(array('message'=>'参数缺失:file_id')));
            $this->validation->add('visible', new InclusionIn(array('message'=>"参数type值只能为:0/1",
                'domain'=>array('0', '1'),
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
            $file_id = (int)$this->params['file_id'];
            $visible = (int)$this->params['visible'];
            $file = Userfile::findFirst(array("id=$file_id and uid=$uid and file_status=".\FileStatus::NORMAL));
            if(!$file)
            {
                $this->responseData = array("code"=>\Code::ERROR,"msg"=>'文件不存在或已删除',"line"=>__LINE__,"data"=>array());
                break;
            }
            if($file->file_type==\FileType::FOLDER)
            {
                $this->responseData = array("code"=>\Code::ERROR,"msg"=>'请勿操作文件夹',"line"=>__LINE__,"data"=>array());
                break;
            }
            $this->db->begin();
            $file->visible = $visible;
            if(!$file->update())
            {
                $this->db->rollback();
                $this->responseData = array("code"=>\Code::ERROR_DB,"msg"=>"设置失败","line"=>__LINE__,"data"=>array());
                break;
            }
            //动态记录和删除
            $user_dynamic = Userdynamic::findFirst("uid=$uid and addition=$file_id and type=".\DynamicType::FileZone);
            if($visible)
            {
                if(!$user_dynamic)
                {
                    $content = sprintf(\DynamicContent::FileZone,$file->file_name);;
                    if(!$this->insertDynamic($uid,$content,$file_id,\DynamicType::FileZone))
                    {
                        $this->db->rollback();
                        $this->responseData = array("code"=>\Code::ERROR_DB,"msg"=>"动态记录失败","line"=>__LINE__,"data"=>array());
                        break;
                    }
                }
                else
                {
                    $user_dynamic->status = 1;
                    $user_dynamic->addtime = date("Y-m-d H:i:s");
                    if(!$user_dynamic->update())
                    {
                        $this->db->rollback();
                        $this->responseData = array("code"=>\Code::ERROR_DB,"msg"=>"设置失败","line"=>__LINE__,"data"=>array());
                        break;
                    }
                }
            }
            else
            {
                $user_dynamic->status = 0;
                if(!$user_dynamic->update())
                {
                    $this->db->rollback();
                    $this->responseData = array("code"=>\Code::ERROR_DB,"msg"=>"设置失败","line"=>__LINE__,"data"=>array());
                    break;
                }
            }
            $this->db->commit();
        }while(false);
        $this->output();
    }

    /**
     * @brief 空间可见列表
     * @param 无
     */
    public function visibleListAction()
    {
        do {
            if(!$this->checkUserLogin())
            {
                break;
            }
            $uid = $this->uid;
            $files = Userfile::find(array("visible=1 and uid=$uid and file_status=".\FileStatus::NORMAL));
            $data = $files->toArray();
            foreach($data as $k=>$v)
            {
                $data[$k] = $v;
                $data[$k]['sizeConv'] = $this->fileSizeConv($v['file_size']);
            }
            $this->responseData['data']['visibleFiles'] = $data;
        }while(false);
        $this->output();
    }

    /**
     * @brief 获得公开文件链接
     * @param file_id
     */
    public function getPublicLinkAction()
    {
//        $this->params = array("file_id"=>1040);
        do {
            $this->validation->add('file_id', new PresenceOf(array('message'=>'参数缺失:file_id')));

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
            $file_id = (int)$this->params['file_id'];
            $file = Userfile::findFirst(array("id=$file_id and uid=$uid and file_status=".\FileStatus::NORMAL));
            if(!$file)
            {
                $this->responseData = array("code"=>\Code::ERROR,"msg"=>'文件不存在或已删除',"line"=>__LINE__,"data"=>array());
                break;
            }
            if($file->file_type==\FileType::FOLDER)
            {
                $this->responseData = array("code"=>\Code::ERROR,"msg"=>'请勿操作文件夹',"line"=>__LINE__,"data"=>array());
                break;
            }
            $userFileShare = Userfileshare::findFirst(array("uid=$uid and user_file_id=$file_id"));
            if(!$userFileShare)
            {
                $this->db->begin();
                $userFileShare = new Userfileshare();
                $userFileShare->uid = $uid;
                $userFileShare->user_file_id = $file_id;
                if(!$userFileShare->save())
                {
                    $this->db->rollback();
                    $this->responseData = array("code"=>\Code::ERROR,"msg"=>'分享失败',"line"=>__LINE__,"data"=>array());
                    break;
                }
                $content = sprintf(\DynamicContent::FileShare,$file->file_name);
                if(!$this->insertDynamic($uid,$content,$file->id,\DynamicType::FileShare))
                {
                    $this->db->rollback();
                    $this->responseData = array("code"=>\Code::ERROR,"msg"=>'动态记录异常，分享失败',"line"=>__LINE__,"data"=>array());
                    break;
                }
                $this->db->commit();
                $this->responseData['data']['url'] = $this->dec2Any($userFileShare->id);
            }
            else
            {
                $this->responseData['data']['url'] = $this->dec2Any($userFileShare->id);
            }

        }while(false);
        $this->output();
    }

    /**
     * @brief Mp3文件预览
     * @param file_id
     */
    public function fileMp3PreviewAction()
    {
        do {
            $this->validation->add('file_id', new PresenceOf(array('message'=>'参数缺失:file_id')));
            $messages = $this->validation->validate($this->params);
            if (count($messages)) {
                foreach ($messages as $message) {
                    array_push($this->errors, strval($message));
                }
                echo join(';', $this->errors);
                break;
            }
            $uid = 0;
            if($this->checkUserLogin())
            {
                $uid = $this->uid;
            }
            $file_id = $this->params['file_id'];
            $file = Userfile::findFirst(array("id=$file_id and file_type=4 and file_status<".\FileStatus::DELETE));
            if(!$file)
            {
                $this->responseData = array("code"=>\Code::ERROR,"msg"=>'文件不存在',"line"=>__LINE__,"data"=>array());
                break;
            }
            if($file->uid != $uid)
            {
                //判断文件是否是公开资源
                $push = Userfilepush::findFirst(array("user_file_id=".$file->id));
                if(!$push)
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
            $mp3Path = APP_PATH.'/public/mp3/'.$file->uid.$file->path;
            if(!is_dir($mp3Path))
            {
                mkdir($mp3Path,0755,true);
            }
            $path = $mp3Path.$file->file_md5.'.mp3';
            if(!file_exists($path))
            {
                //MP3文件地址
                if($file->file_status==\FileStatus::NORMAL)
                {
                    $filePath = $this->config->upload_path.$file->uid.$file->path.$file->file_name;
                }
                else
                {
                    $filePath = $this->config->upload_recoverpool.$file->uid.$file->path.$file->file_name;
                }
                symlink(readlink($filePath),$path);
            }
            $this->responseData['data']['path'] = '/mp3/'.$file->uid.$file->path.$file->file_md5.'.mp3';
        }while(false);
        $this->output();
    }

    /**
     * @brief 【分页】获取回收站的用户文件&文件夹
     * @param （选填）page 当前页 默认是：1
     * @param （选填）limit 每页显示条数 默认是：12
     */
    public function getTrashFilesByPageAction()
    {
        do {
            if(!$this->checkUserLogin())
            {
                break;
            }
            $uid = $this->uid;
            $limit = isset($this->params['limit'])&&(int)$this->params['limit']>1?(int)$this->params['limit']:12;
            $page = isset($this->params['page'])&&(int)$this->params['page']>0?(int)$this->params['page']:1;

            $where_fold = "uid=$uid and del_type=1 and file_status=".\FileStatus::RECOVER." and file_type=".\FileType::FOLDER;
            $where_file = "uid=$uid and percent=100 and del_type=1 and file_status=".\FileStatus::RECOVER." and file_type>".\FileType::FOLDER;
            $offset = ($page - 1) * $limit;
            $fold_cnt = Userfile::count(array($where_fold));//目录总数
            $file_cnt = Userfile::count(array($where_file)); //文件总数
            $total_rows = $fold_cnt + $file_cnt; // 目录和文件总和
            $this->responseData['data']['page'] = $page;
            $this->responseData['data']['total_rows'] = $total_rows;
            $this->responseData['data']['page_count'] = ceil($total_rows / $limit);
            $folder_rows = Userfile::find(array($where_fold,"limit"=>$limit,"offset"=>$offset,"order"=>\FileSortType::$TYPES[1]));
            $residue = $limit - count($folder_rows);
            if ($residue > 0) {

                $offset = (($page - 1) * $limit - $fold_cnt)>0?(($page - 1) * $limit - $fold_cnt):0;
                $file_rows = Userfile::find(array($where_file,"limit"=>$residue,"offset"=>$offset,"order"=>\FileSortType::$TYPES[1]));
                $data = array_merge($folder_rows->toArray(),$file_rows->toArray());
            }
            else
            {
                $data = $folder_rows->toArray();
            }
            foreach($data as $k=>$v)
            {
                $data[$k] = $v;
                $data[$k]['sizeConv'] = $this->fileSizeConv($v['file_size']);
            }
            $this->responseData['data']['userFiles'] = $data;
        }while(false);
        $this->output();
    }

    /**
     * @brief 根据目录获取回收站的用户文件&文件夹
     * @param path 文件相对路径；例：/  /{文件夹ID}/
     */
    public function getTrashFilesByPathAction()
    {
//        $this->params = array('path'=>'/1169/','type'=>0,'page'=>1,'limit'=>12);
        do {
            $this->validation->add('path', new PresenceOf(array('message'=>'参数缺失:path')));
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
            $path = $this->params['path'];
            $limit = isset($this->params['limit'])&&(int)$this->params['limit']>1?(int)$this->params['limit']:99999;
            $page = isset($this->params['page'])&&(int)$this->params['page']>0?(int)$this->params['page']:1;
            $where_fold = "uid=$uid and path='$path' and del_type=2 and file_status=".\FileStatus::RECOVER." and file_type=".\FileType::FOLDER;
            $where_file = "uid=$uid and percent=100 and del_type=2 and file_status=".\FileStatus::RECOVER." and file_type>".\FileType::FOLDER;
            $offset = ($page - 1) * $limit;
            $fold_cnt = Userfile::count(array($where_fold));//目录总数
            $file_cnt = Userfile::count(array("$where_file and path='$path'")); //文件总数
            $total_rows = $fold_cnt + $file_cnt; // 目录和文件总和
            $this->responseData['data']['page'] = $page;
            $this->responseData['data']['total_rows'] = $total_rows;
            $this->responseData['data']['page_count'] = ceil($total_rows / $limit);
            $folder_rows = Userfile::find(array($where_fold,"limit"=>$limit,"offset"=>$offset,"order"=>\FileSortType::$TYPES[1]));
            $residue = $limit - count($folder_rows);
            if ($residue > 0) {

                $offset = (($page - 1) * $limit - $fold_cnt)>0?(($page - 1) * $limit - $fold_cnt):0;
                $file_rows = Userfile::find(array("$where_file and path='$path'","limit"=>$residue,"offset"=>$offset,"order"=>\FileSortType::$TYPES[1]));
                $data = array_merge($folder_rows->toArray(),$file_rows->toArray());
            }
            else
            {
                $data = $folder_rows->toArray();
            }
            foreach($data as $k=>$v)
            {
                $data[$k] = $v;
                $data[$k]['sizeConv'] = $this->fileSizeConv($v['file_size']);
            }
            $this->responseData['data']['userFiles'] = $data;
        }while(false);
        $this->output();
    }

    /**
     * @brief 还原回收站文件或文件夹到目录
     * @param file_id   文件ID
     * @param is_force (选填)是否强制重命名 0：否（默认） 1：是
     */
    public function recoverFileAction()
    {
//        $this->params = array('file_id'=>11);
        do {
            $this->validation->add('file_id', new PresenceOf(array('message'=>'参数缺失:file_id')));
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
            $file_id = $this->params['file_id'];
            $is_force = isset($this->params['is_force'])?(int)$this->params['is_force']:0;
            $file = Userfile::findFirst(array("id=$file_id and uid=$uid and del_type=1 and file_status=".\FileStatus::RECOVER));
            if(!$file)
            {
                $this->responseData = array("code"=>\Code::ERROR,"msg"=>'文件或文件夹恢复失败',"line"=>__LINE__,"data"=>array());
                break;
            }
            $old_file_name = $file->file_name;
            $new_file_name = $file->file_name;
            $sameNameFile = Userfile::findFirst(array("path='".$file->path."' and file_name='$new_file_name' and uid=$uid and file_status=".\FileStatus::NORMAL." and id!=".$file->id));
            if($sameNameFile)
            {
                if($is_force==1)    //强制重命名
                {
                    $new_file_name = $this->getLastFileName($uid,$file->id,$file->file_name,$file->path);
                }
                else
                {
                    $this->responseData = array("code"=>\Code::ERROR_DB_WRITE,"msg"=>'还原的位置已经包含了同名的文件夹，是否要保存两个文件',"line"=>__LINE__,"data"=>array());
                    break;
                }
            }
            $this->db->begin();
            $file->file_name = $new_file_name;
            $file->file_status = \FileStatus::NORMAL;
            $file->del_type = 0;
            if(!$file->update())
            {
                $this->db->rollback();
                $this->responseData = array("code"=>\Code::ERROR_DB,"msg"=>'文件还原失败',"line"=>__LINE__,"data"=>array());
                break;
            }
            $f = new \FileUtil();
            if($file->file_type==\FileType::FOLDER)
            {
                //更新子文件的状态
                $child_path = $file->path.$file->id.'/';
                $sql = "select * from edu_user_file where uid=$uid and del_type=2 and file_status=".\FileStatus::RECOVER." and left(path,".strlen($child_path).") ='".$child_path."'";
                $allFiles = new Userfile();
                $allFiles = new Resultset(null, $allFiles, $allFiles->getReadConnection()->query($sql));
                $allFileIds = array();
                foreach($allFiles as $k=>$allFile)
                {
                    array_push($allFileIds,$allFile->id);
                }
                if(!empty($allFileIds))
                {
                    $sql = "update edu_user_file set file_status=".\FileStatus::NORMAL.",del_type=0 where id in(".join(',',$allFileIds).")";
                    $update = $this->db->query($sql);
                    if(!$update)
                    {
                        $this->db->rollback();
                        $this->responseData = array("code"=>\Code::ERROR_DB_WRITE,"msg"=>'文件还原失败',"line"=>__LINE__,"data"=>array());
                        break;
                    }
                }
                if(!$f->moveDir($this->config->upload_recoverpool.$uid.$file->path.$file->id,$this->config->upload_path.$uid.$file->path.$file->id,true))
                {
                    $this->db->rollback();
                    $this->responseData = array("code"=>\Code::ERROR_DB_WRITE,"msg"=>'文件还原失败[服务器]',"line"=>__LINE__,"data"=>array());
                    break;
                }
            }
            else
            {
                if(!$f->moveFile($this->config->upload_recoverpool.$uid.$file->path.$old_file_name,$this->config->upload_path.$uid.$file->path.$new_file_name,true))
                {
                    $this->db->rollback();
                    $this->responseData = array("code"=>\Code::ERROR_DB_WRITE,"msg"=>'文件还原失败[服务器]',"line"=>__LINE__,"data"=>array());
                    break;
                }
            }
            $this->db->commit();
            $this->responseData['data']['name'] = $new_file_name;
        }while(false);
        $this->output();
    }

    private function getLastFileName($uid,$file_id,$file_name,$folder_path)
    {
        $fileCount = Userfile::count(array("path='".$folder_path."' and uid=$uid and file_status=".\FileStatus::NORMAL." and id!=".$file_id));
        $ext = strrchr($file_name, '.');
        $fname = basename($file_name,$ext);
        $result = array();
        preg_match_all("/(\(([0-9]*)\)$)/i",$fname,$result);
        for($i=1;$i<$fileCount;$i++)
        {
            if(!empty($result[2][0])&&$result[2][0]>=0)
            {

                $file_name = preg_replace ("/(\(([0-9]*)\)$)/i",'('.($result[2][0]+$i).')',$fname).$ext;
            }
            else
            {
                $file_name = $fname.'('.$i.')'.$ext;
            }
            $matchSameNameFile = Userfile::findFirst(array("path='".$folder_path."' and file_name='$file_name' and uid=$uid and file_status=".\FileStatus::NORMAL." and id!=".$file_id));
            if(!$matchSameNameFile)
            {
                break;
            }
            else
            {
                unset($matchSameNameFile);
                continue;
            }
        }
        return $file_name;
    }

    /**
     * @brief 记录录像到网盘
     * @param live_id   直播ID
     * @param file_name 文件名
     */
    public function recordBoxAction()
    {
//        $this->params = array('uid'=>124,'file_name'=>'liveVideo-1.mp4');
        do {
            $this->validation->add('live_id', new PresenceOf(array('message'=>'参数缺失:live_id')));
            $this->validation->add('file_name', new PresenceOf(array('message'=>'参数缺失:file_name')));
            $messages = $this->validation->validate($this->params);
            if (count($messages)) {
                foreach ($messages as $message) {
                    array_push($this->errors, strval($message));
                }
                $this->responseData = array("code"=>\Code::ERROR_PARAMS,"msg"=>join(';', $this->errors),"line"=>__LINE__,"data"=>array());
                break;
            }
            $live_id = $this->params['live_id'];
            $live = Live::findFirst(array("id=$live_id"));
            if(!$live)
            {
                $this->responseData = array("code"=>\Code::ERROR_DB,"msg"=>'直播不存在',"line"=>__LINE__,"data"=>array());
                break;
            }
            $uid = $live->uid;
            $file_name = $this->params['file_name'];
            $file_path = $this->config->upload_path.$uid;
            if(!is_dir($file_path))
            {
                mkdir($file_path,0755,true);
            }
            if(!file_exists($file_path.'/'.$file_name))
            {
                $this->responseData = array("code"=>\Code::ERROR_DB,"msg"=>'录像不存在',"line"=>__LINE__,"data"=>array());
                break;
            }
            $size = filesize($file_path.'/'.$file_name);
            $md5= md5_file($file_path.'/'.$file_name);
            $date = date('Y-m-d');
            $time = time();
            $user = Userinfo::findFirst(array("uid=$uid"));
            if(!$user)
            {
                $this->responseData = array("code"=>\Code::ERROR_DB,"msg"=>'用户不存在',"line"=>__LINE__,"data"=>array());
                break;
            }
            $userCapacity = Usercapacity::findFirst("uid=$uid");
            if(!$userCapacity)
            {
                $this->responseData = array("code"=>\Code::ERROR_DB,"msg"=>'用户异常',"line"=>__LINE__,"data"=>array());
                break;
            }
            if(($userCapacity->capacity_used+$size)>$userCapacity->capacity_all)
            {
                $this->responseData = array("code"=>\Code::ERROR_DB,"msg"=>'文件大小超出用户空间容量',"line"=>__LINE__,"data"=>array());
                break;
            }
            $filePool = Files::findFirst(array("md5='$md5'"));
            if($filePool)
            {
                $this->responseData = array("code"=>\Code::ERROR_DB,"msg"=>'录像已存在',"line"=>__LINE__,"data"=>array());
                break;
            }
            $filePoolPath = $this->config->upload_filepool.$date;
            if(!is_dir($filePoolPath))
            {
                mkdir($filePoolPath,0755,true);
            }
            $f = new \FileUtil();
            if(!$f->moveFile($file_path.'/'.$file_name,$filePoolPath.'/'.$md5,true))
            {
                $this->responseData = array("code"=>\Code::ERROR_DB_WRITE,"msg"=>'文件移动文件池失败',"line"=>__LINE__,"data"=>array());
                break;
            }
            //存入直播池
            $livePoolPath = $this->config->upload_path.'live/'.$uid;
            if(!is_dir($livePoolPath))
            {
                mkdir($livePoolPath,0755,true);
            }
            symlink($filePoolPath.'/'.$md5,$livePoolPath.'/'.$file_name);
            $new_file_name = $file_name;
            $sameNameFile = Userfile::findFirst(array("path='/' and file_name='$new_file_name' and uid=$uid and file_status=".\FileStatus::NORMAL));
            if($sameNameFile)
            {
                $new_file_name = $this->getLastFileName($uid,0,$file_name,'/');
            }
            symlink($filePoolPath.'/'.$md5,$file_path.'/'.$new_file_name);
            $videoThumbPath = $this->config->upload_video_thumb.$date;
            if(!is_dir($videoThumbPath))
            {
                mkdir($videoThumbPath,0755,true);
            }
            exec( 'ffmpeg -i "'.$file_path.'/'.$new_file_name.'" -y -f image2 -ss 1 -t 0.001 -s 600*450 '.$videoThumbPath.'/'.$md5.'.png');
            $this->db->begin();
            //记录直播池录像地址
            $live->video_path = 'live/'.$uid.'/'.$file_name;
            $live->end_time = date('Y-m-d H:i:s');
            $live->status = 2;
            if(!$live->update())
            {
                $this->db->rollback();
                $this->responseData = array("code"=>\Code::ERROR_DB,"msg"=>'记录直播池录像地址失败',"line"=>__LINE__,"data"=>array());
                break;
            }
            $filePool = new Files();
            $filePool->size = $size;
            $filePool->md5 = $md5;
            $filePool->path = date('Y-m-d');
            $filePool->video_thumb = $md5.'.png';
            $filePool->file_count = 1;
            $filePool->addtime = $time;
            if(!$filePool->create())
            {
                $this->db->rollback();
                $this->responseData = array("code"=>\Code::ERROR_DB,"msg"=>'插入文件池失败',"line"=>__LINE__,"data"=>array());
                break;
            }
            $userFile = new userFile();
            $userFile->uid = $uid;
            $userFile->file_name = $new_file_name;
            $userFile->path = '/';
            $userFile->file_from = 0;
            $userFile->file_status = 0;
            $userFile->file_type = 2;
            $userFile->file_id = $filePool->id;
            $userFile->file_size = $size;
            $userFile->file_md5 = $md5;
            $userFile->percent = 100;
            $userFile->video_thumb = $date.'/'.$md5.'.png';
            $userFile->visible = 0;
            $userFile->del_type = 0;
            $userFile->addtime = $time;
            $userFile->download_count = 0;
            $userFile->is_video = $live->id;
            if(!$userFile->create())
            {
                $this->db->rollback();
                $this->responseData = array("code"=>\Code::ERROR_DB,"msg"=>'插入文件失败',"line"=>__LINE__,"data"=>array());
                break;
            }
            $this->db->commit();
        }while(false);
        $this->output();
    }

    /**
     * @brief 添加知识点、切片
     * @param live_id   直播ID
     * @param type 1、知识点  2、切片
     * @param name 名称
     * @param start_time 开始时间
     * @param end_time 结束时间  【type=2时 必传】
     */
    public function addLiveVideoInfoAction()
    {
//        $this->params = array('uid'=>124,'file_name'=>'liveVideo-1.mp4');
        do {
            $this->validation->add('live_id', new PresenceOf(array('message'=>'参数缺失:live_id')));
            $this->validation->add('type', new PresenceOf(array('message'=>'参数缺失:type')));
            $this->validation->add('name', new PresenceOf(array('message'=>'参数缺失:name')));
            $this->validation->add('start_time', new PresenceOf(array('message'=>'参数缺失:start_time')));
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
            $live_id = $this->params['live_id'];
            $type = $this->params['type'];
            $name = $this->params['name'];
            $start_time = $this->params['start_time'];
            $end_time = $this->params['end_time'];
            $live = Live::findFirst(array("id=$live_id and uid=$uid"));
            if(!$live)
            {
                $this->responseData = array("code"=>\Code::ERROR_DB,"msg"=>'直播不存在',"line"=>__LINE__,"data"=>array());
                break;
            }
            $liveVideoInfo = new Livevideoinfo();
            $liveVideoInfo->live_id = $live_id;
            $liveVideoInfo->type = $type;
            $liveVideoInfo->name = $name;
            $liveVideoInfo->start_time = $start_time;
            if($type==2)
            {
                $liveVideoInfo->end_time = $end_time;
            }
            if(!$liveVideoInfo->create())
            {
                $this->responseData = array("code"=>\Code::ERROR_DB,"msg"=>'添加录像信息失败',"line"=>__LINE__,"data"=>array());
                break;
            }
        }while(false);
        $this->output();
    }

    /**
     * @brief 修改知识点、切片
     * @param id
     * @param name 名称
     */
    public function editLiveVideoInfoAction()
    {
//        $this->params = array('uid'=>124,'file_name'=>'liveVideo-1.mp4');
        do {
            $this->validation->add('id', new PresenceOf(array('message'=>'参数缺失:id')));
            $this->validation->add('name', new PresenceOf(array('message'=>'参数缺失:name')));
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
            $id = $this->params['id'];
            $name = $this->params['name'];
            $liveVideoInfo = Livevideoinfo::findFirst(array("id=$id"));
            if(!$liveVideoInfo)
            {
                $this->responseData = array("code"=>\Code::ERROR_DB,"msg"=>'录像信息不存在',"line"=>__LINE__,"data"=>array());
                break;
            }
            $live_id = $liveVideoInfo->live_id;
            $live = Live::findFirst(array("id=$live_id and uid=$uid"));
            if(!$live)
            {
                $this->responseData = array("code"=>\Code::ERROR_DB,"msg"=>'直播不存在',"line"=>__LINE__,"data"=>array());
                break;
            }
            $liveVideoInfo->name = $name;
            if(!$liveVideoInfo->update())
            {
                $this->responseData = array("code"=>\Code::ERROR_DB,"msg"=>'编辑录像信息失败',"line"=>__LINE__,"data"=>array());
                break;
            }
        }while(false);
        $this->output();
    }

    /**
     * @brief 获取知识点、切片列表
     * @param live_id 类型
     */
    public function getLiveVideoInfoListAction()
    {
        do {
            $this->validation->add('live_id', new PresenceOf(array('message'=>'参数缺失:live_id')));
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
            $live_id = $this->params['live_id'];
            $liveVideoInfoList = Livevideoinfo::find(array("type=1 and live_id=$live_id","order"=>"start_time asc"));
            $this->responseData['data']['knowlegeList'] = $liveVideoInfoList->toArray();
            $liveVideoInfoList = Livevideoinfo::find(array("type=2 and live_id=$live_id","order"=>"addtime asc"));
            $this->responseData['data']['qiepianList'] = $liveVideoInfoList->toArray();
        }while(false);
        $this->output();
    }

    /**
     * @brief 删除知识点、切片
     * @param id
     */
    public function delLiveVideoInfoAction()
    {
        do {
            $this->validation->add('id', new PresenceOf(array('message'=>'参数缺失:id')));
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
            $id = $this->params['id'];
            $liveVideoInfo = Livevideoinfo::findFirst(array("id=$id"));
            if(!$liveVideoInfo)
            {
                $this->responseData = array("code"=>\Code::ERROR_DB,"msg"=>'录像信息不存在',"line"=>__LINE__,"data"=>array());
                break;
            }
            $live_id = $liveVideoInfo->live_id;
            $live = Live::findFirst(array("id=$live_id and uid=$uid"));
            if(!$live)
            {
                $this->responseData = array("code"=>\Code::ERROR_DB,"msg"=>'直播不存在',"line"=>__LINE__,"data"=>array());
                break;
            }
            if(!$liveVideoInfo->delete())
            {
                $this->responseData = array("code"=>\Code::ERROR_DB,"msg"=>'删除录像信息失败',"line"=>__LINE__,"data"=>array());
                break;
            }
        }while(false);
        $this->output();
    }

    /**
     * @brief 根据id组获取文件详情列表
     * @param ids
     */
    public function getFileNameByIdsAction()
    {
        do {
            $this->validation->add('ids', new PresenceOf(array('message'=>'参数缺失:ids')));
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
            $ids = $this->params['ids'];
            $userFiles = Userfile::find(array("uid=$uid and id in($ids)"));
            $this->responseData['data']['userFileList'] = $userFiles->toArray();
        }while(false);
        $this->output();
    }

    /**
     * @brief 添加文档转换任务
     * @param user_file_id   文件ID
     */
    public function addConvertTaskAction()
    {
//        $this->params = array('user_file_id'=>903);
        do {
            $this->validation->add('user_file_id', new PresenceOf(array('message'=>'参数缺失:user_file_id')));
            $messages = $this->validation->validate($this->params);
            if (count($messages)) {
                foreach ($messages as $message) {
                    array_push($this->errors, strval($message));
                }
                $this->responseData = array("code"=>\Code::ERROR_PARAMS,"msg"=>join(';', $this->errors),"line"=>__LINE__,"data"=>array());
                break;
            }
            $user_file_id = $this->params['user_file_id'];
            $userFile = Userfile::findFirst(array("id=$user_file_id and file_type=5"));
            if(!$userFile)
            {
                $this->responseData = array("code"=>\Code::ERROR_DB,"msg"=>'转换文档不存在',"line"=>__LINE__,"data"=>array());
                break;
            }
            $task = Converttask::findFirst(array("master_id=$user_file_id and table_name='edu_user_file'"));
            if($task)
            {
                $this->responseData = array("code"=>\Code::ERROR_DB,"msg"=>'转换任务已存在',"line"=>__LINE__,"data"=>array());
                break;
            }
            $converTask = new Converttask();
            $converTask->table_name = 'edu_user_file';
            $converTask->field_name = $userFile->file_name;
            $converTask->master_id = $user_file_id;
            $converTask->file_path = $this->config->upload_path.$userFile->uid.$userFile->path.$userFile->file_name;
            $converTask->target_path = $this->config->upload_previewpool.$userFile->uid.'/'.$user_file_id;
            if(!$converTask->create())
            {
                $this->responseData = array("code"=>\Code::ERROR_DB,"msg"=>'添加转换任务失败',"line"=>__LINE__,"data"=>array());
                break;
            }
        }while(false);
        $this->output();
    }

    /**
     * @brief 定时批量添加文档转换任务
     */
    public function timeAddConvertTaskAction()
    {
//        $this->params = array('user_file_id'=>903);
        do {
            $userFiles = Userfile::find(array("file_type=".\FileType::DOC));
            $fileIdArr = array();
            $fileArr = array();
            foreach($userFiles as $userFile)
            {
                array_push($fileIdArr,$userFile->id);
                $fileArr[$userFile->id]['id'] = $userFile->id;
                $fileArr[$userFile->id]['name'] = $userFile->file_name;
                $fileArr[$userFile->id]['file_path'] = $this->config->upload_path.$userFile->uid.$userFile->path.$userFile->file_name;
                $fileArr[$userFile->id]['target_path'] = $this->config->upload_previewpool.$userFile->uid.'/'.$userFile->id;
            }
            $tasks = Converttask::find(array("table_name='edu_user_file'"));
            $masterIdArr = array();
            foreach($tasks as $task)
            {
                array_push($masterIdArr,$task->master_id);
            }
            $ids = array_diff($fileIdArr,$masterIdArr);
            foreach($ids as $id)
            {
                $converTask = new Converttask();
                $converTask->table_name = 'edu_user_file';
                $converTask->field_name = $fileArr[$id]['name'];
                $converTask->master_id = $fileArr[$id]['id'];
                $converTask->file_path = $fileArr[$id]['file_path'];
                $converTask->target_path = $fileArr[$id]['target_path'];
                $converTask->create();
            }
        }while(false);
        $this->output();
    }

    /**
     * @brief 【分页】获取文档图片
     * @param （选填）page 当前页 默认是：1
     * @param （选填）limit 每页显示条数 默认是：1
     * @param （选填）sort 资源排序1：按时间降序（默认）2：按时间升序 3：按文件名升序 4:按文件名降序5:按文件大小降序6:按文件大小升序
     * @param （选填）keywords 名称搜索关键词
     */
    public function getResourcesByPageAction()
    {
        $this->params = array('page'=>1,'limit'=>5);
        do {
            if(!$this->checkUserLogin())
            {
                break;
            }
            $uid = $this->uid;
            $limit = isset($this->params['limit'])&&(int)$this->params['limit']>1?(int)$this->params['limit']:10;
            $page = isset($this->params['page'])&&(int)$this->params['page']>0?(int)$this->params['page']:1;
            $sort = isset($this->params['sort'])&&(int)$this->params['sort']>0?$this->params['sort']:\FileSortType::DOWNTIME;
            $keywords = isset($this->params['keywords'])?$this->params['keywords']:null;
            $condition = '';
            $where_file = "uid=$uid and percent=100 and file_status=".\FileStatus::NORMAL." and file_type>".\FileType::FOLDER;
            $offset = ($page - 1) * $limit;
            if(!empty($keywords))
            {
                $condition = " file_name like '%$keywords%' and ";
            }
            $total_rows = Userfile::count(array("$condition $where_file and (file_type=".\FileType::DOC." or file_type=".\FileType::IMAGE.")"));
            $userFiles = Userfile::find(array("$condition $where_file and (file_type=".\FileType::DOC." or file_type=".\FileType::IMAGE.")","limit"=>$limit,"offset"=>$offset,"order"=>\FileSortType::$TYPES[$sort]));
            $this->responseData['data']['page'] = $page;
            $this->responseData['data']['total_rows'] = $total_rows;
            $this->responseData['data']['page_count'] = ceil($total_rows / $limit);
            $data = $userFiles->toArray();
            foreach($data as $k=>$v)
            {
                $data[$k] = $v;
                $data[$k]['sizeConv'] = $this->fileSizeConv($v['file_size']);
            }
            $this->responseData['data']['userFiles'] = $data;
        }while(false);
        $this->output();
    }
}