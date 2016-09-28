<?php
namespace Cloud\Frontend\Controllers;

use Cloud\Models\Userfile;
use Cloud\Models\Userinfo;
use Cloud\Models\Userfilecollect;
use Cloud\Models\Userfilepush;
use Cloud\Models\Userfileinfo;
use Cloud\Models\Subject;
use Cloud\Models\Userfilecomment;
use Cloud\Models\Userfileshare;
use Cloud\Models\Applicationtype;
use Phalcon\Mvc\Model\Resultset\Simple as Resultset;
class FileController extends ControllerBase
{
    public function indexAction()
    {
		if($this->bSignedIn){
			$type = $this->request->get('type');
			$uid = $this->userInfo->uid;
			$path = '/';

			$userFolder = Userfile::find(array("uid=$uid and path='$path' and file_status=".\FileStatus::NORMAL." and file_type=".\FileType::FOLDER));

			$userFiles = Userfile::find(array("uid=$uid and path='$path' and percent=100 and file_status =".\FileStatus::NORMAL." and file_type>".\FileType::FOLDER,"order"=>\FileSortType::$TYPES[\FileSortType::DOWNTIME],'limit'=>12));


			$filesArray = array_merge($userFolder->toArray(),$userFiles->toArray());
			foreach($filesArray as $key=>$userFile){
				$filesArray[$key]['fileSize'] = $this->fileSizeConv($userFile['file_size']);
			}
			$this->view->userFiles = $filesArray;
			$appType = Applicationtype::find(array());
			$applicationType = $appType->toArray();
			$fileLanguage =\FileLanguage::$Language;
			$this->view->applicationTypes = $applicationType;
			$this->view->fileLanguages = $fileLanguage;
			$this->view->type = $type;
		}
		else{
			$this->response->redirect("/login/login");
		}

    }
	//获取直播录像列表
	public function getliveAction()
	{
		if($this->bSignedIn){
			$uid = $this->userInfo->uid;
			$userObject = Userfile::find(array("uid=$uid  and (percent=100 and file_type=".\FileType::VIDEO.") and file_status=".\FileStatus::NORMAL." ","order"=>"file_type asc,addtime desc,id desc",'limit'=>12,'offset'=>0));
			$userFiles = $userObject->toArray();
			foreach($userFiles as $key=>$userFile){
				$fileSize = $this->fileSizeConv($userFile['file_size']);
				$userFiles[$key]['fileSize'] = $fileSize;
			}
			$this->view->userFiles = $userFiles;
			$applicationType =\FileApplicationType::$ApplicationType;
			$fileLanguage =\FileLanguage::$Language;
			$this->view->applicationTypes = $applicationType;
			$this->view->fileLanguages = $fileLanguage;
		}
		else{
			$this->response->redirect("/login/login");
		}
	}
	public function getvideoAction()
	{
		if($this->bSignedIn){
			$uid = $this->userInfo->uid;
			$userObject = Userfile::find(array("uid=$uid  and (percent=100 and file_type=".\FileType::VIDEO.") and file_status=".\FileStatus::NORMAL." ","order"=>"file_type asc,addtime desc,id desc",'limit'=>12,'offset'=>0));
			$userFiles = $userObject->toArray();
			foreach($userFiles as $key=>$userFile){
				$fileSize = $this->fileSizeConv($userFile['file_size']);
				$userFiles[$key]['fileSize'] = $fileSize;
			}
			$this->view->userFiles = $userFiles;
			$applicationType =\FileApplicationType::$ApplicationType;
			$fileLanguage =\FileLanguage::$Language;
			$this->view->applicationTypes = $applicationType;
			$this->view->fileLanguages = $fileLanguage;
		}
		else{
			$this->response->redirect("/login/login");
		}
	}
	public function getpicAction()
	{
		if($this->bSignedIn){
			$uid = $this->userInfo->uid;
			$userObject = Userfile::find(array("uid=$uid  and (percent=100 and file_type=".\FileType::IMAGE.") and file_status=".\FileStatus::NORMAL." ","order"=>"file_type asc,addtime desc,id desc",'limit'=>12,'offset'=>0));
			$userFiles = $userObject->toArray();
			foreach($userFiles as $key=>$userFile){
				$fileSize = $this->fileSizeConv($userFile['file_size']);
				$userFiles[$key]['fileSize'] = $fileSize;
			}
			$this->view->userFiles = $userFiles;

			$applicationType =\FileApplicationType::$ApplicationType;
			$fileLanguage =\FileLanguage::$Language;
			$this->view->applicationTypes = $applicationType;
			$this->view->fileLanguages = $fileLanguage;
		}
		else{
			$this->response->redirect("/login/login");
		}
	}
	public function getaudioAction()
	{
		if($this->bSignedIn){
			$uid = $this->userInfo->uid;
			$userObject = Userfile::find(array("uid=$uid  and (percent=100  and file_type=".\FileType::AUDIO.") and file_status=".\FileStatus::NORMAL."","order"=>"file_type asc,addtime desc,id desc",'limit'=>12,'offset'=>0));
			$userFiles = $userObject->toArray();
			foreach($userFiles as $key=>$userFile){
				$fileSize = $this->fileSizeConv($userFile['file_size']);
				$userFiles[$key]['fileSize'] = $fileSize;
			}
			$this->view->userFiles = $userFiles;

			$applicationType =\FileApplicationType::$ApplicationType;
			$fileLanguage =\FileLanguage::$Language;
			$this->view->applicationTypes = $applicationType;
			$this->view->fileLanguages = $fileLanguage;
		}
		else{
			$this->response->redirect("/login/login");
		}
	}
	public function getcollectAction()
	{
		if($this->bSignedIn){
			$uid = $this->userInfo->uid;
			$sql = "select t1.addtime as times,t1.collect_date_folder,t1.collect_file_name,t2.* from edu_user_file_collect as t1 left join edu_user_file as t2 on t1.user_file_id=t2.id where t1.uid = $uid  order by t1.addtime desc";
			$userFileCollect = new Userfilecollect();
			$userObject = new Resultset(null, $userFileCollect, $userFileCollect->getReadConnection()->query($sql));
			$userFiles = $userObject->toArray();
			foreach($userFiles as $key=>$userFile){
				$fileSize = $this->fileSizeConv($userFile['file_size']);
				$userFiles[$key]['fileSize'] = $fileSize;
			}

			$this->view->userFiles = $userFiles;

			$applicationType =\FileApplicationType::$ApplicationType;
			$fileLanguage =\FileLanguage::$Language;
			$this->view->applicationTypes = $applicationType;
			$this->view->fileLanguages = $fileLanguage;
		}
		else{
			$this->response->redirect("/login/login");
		}
	}
	public function getvisibleAction()
	{
		if($this->bSignedIn){
			$uid = $this->userInfo->uid;
			$userObject = Userfile::find(array("uid=$uid and visible=1  and file_status=".\FileStatus::NORMAL."","order"=>"file_type asc,addtime desc,id desc"));
			$userFiles = $userObject->toArray();
			foreach($userFiles as $key=>$userFile){
				$fileSize = $this->fileSizeConv($userFile['file_size']);
				$userFiles[$key]['fileSize'] = $fileSize;
			}
			$this->view->userFiles = $userFiles;

			$applicationType =\FileApplicationType::$ApplicationType;
			$fileLanguage =\FileLanguage::$Language;
			$this->view->applicationTypes = $applicationType;
			$this->view->fileLanguages = $fileLanguage;
		}
		else{
			$this->response->redirect("/login/login");
		}
	}
	public function getdocAction()
	{
		if($this->bSignedIn){
			$uid = $this->userInfo->uid;
			$userObject = Userfile::find(array("uid=$uid  and (percent=100 and file_status=".\FileStatus::NORMAL." and file_type=".\FileType::DOC.")","order"=>"file_type asc,addtime desc,id desc",'limit'=>12,'offset'=>0));
			$userFiles = $userObject->toArray();
			foreach($userFiles as $key=>$userFile){
				$fileSize = $this->fileSizeConv($userFile['file_size']);
				$userFiles[$key]['fileSize'] = $fileSize;
			}
			$this->view->userFiles = $userFiles;

			$applicationType =\FileApplicationType::$ApplicationType;
			$fileLanguage =\FileLanguage::$Language;
			$this->view->applicationTypes = $applicationType;
			$this->view->fileLanguages = $fileLanguage;
		}
		else{
			$this->response->redirect("/login/login");
		}
	}
	public function getotherAction()
	{
		if($this->bSignedIn){
			$uid = $this->userInfo->uid;
			$userObject = Userfile::find(array("uid=$uid  and ((percent=100  and file_type>".\FileType::ZIP.") or file_type=".\FileType::ZIP.") and file_status=".\FileStatus::NORMAL."","order"=>"file_type asc,addtime desc,id desc",'limit'=>12,'offset'=>0));
			$userFiles = $userObject->toArray();
			foreach($userFiles as $key=>$userFile){
				$fileSize = $this->fileSizeConv($userFile['file_size']);
				$userFiles[$key]['fileSize'] = $fileSize;
			}
			$this->view->userFiles = $userFiles;

			$applicationType =\FileApplicationType::$ApplicationType;
			$fileLanguage =\FileLanguage::$Language;
			$this->view->applicationTypes = $applicationType;
			$this->view->fileLanguages = $fileLanguage;
		}
		else{
			$this->response->redirect("/login/login");
		}
	}
	/*分享查看页*/
	public function shareAction($code)
	{
		if(!$code)
		{
			$this->response->redirect("/login/login");
			exit;
		}
		$shareId = (int)$this->any2Dec($code);
		$userFileShare = Userfileshare::findFirst(array("id=$shareId and status=0"));
		if(!$userFileShare)
		{
			$this->response->redirect("/login/login");
			exit;
		}
		$userFile = Userfile::findFirst(array("id=".$userFileShare->user_file_id));
		if(!$userFile)
		{
			$this->response->redirect("/login/login");
			exit;
		}
		else
		{
			if($userFile->file_status==\FileStatus::NORMAL)
			{
				$this->view->status = 1;
			}
			else
			{
				$this->view->status = 0;
			}
			$userFile->file_size = $this->fileSizeConv($userFile->file_size);
			$this->view->share_time = $userFileShare->addtime;
			$this->view->fileInfo = $userFile;
		}
	}

	/*文件详情页*/
	public function detailAction($fid=0)
	{
		if($fid == 0){
			$this->response->redirect("index");
		}else{
			$file_id = $fid;
			$file = Userfile::findFirst(array("id = $file_id"));
			if(!$file)
			{
				$this->responseData = array("code"=>\Code::ERROR_DB,"msg"=>'文件不存在',"line"=>__LINE__,"data"=>array());
				$this->response->redirect("index");
			}
			$filePushCount = Userfilepush::count(array("user_file_id=$file_id"));
			if($filePushCount==0)
			{
				$this->responseData = array("code"=>\Code::ERROR_DB,"msg"=>'文件未发布',"line"=>__LINE__,"data"=>array());
				$this->response->redirect("index");
			}
			$fileInfoOb = Userfileinfo::findFirst(array("user_file_id=$file_id"));
			$fileInfo = $fileInfoOb->toArray();
			$fileInfo['userInfo'] = Userinfo::findFirst(array("uid=$file->uid"))->toArray();
			$fileDetail = array_merge($file->toArray(),$fileInfo);
			/*文件应用类型*/
			$FileAppType = \FileApplicationType::$ApplicationType;
			foreach($FileAppType as $value){
				if($value['id'] == $fileDetail['application_type']){
					$fileDetail['application_type_name'] = $value['name'];
				}
			}
			/*文件所属学科*/
			$subject =Subject::findFirst("id=".$fileDetail['subject_id']);
			$subFather = Subject::findFirst("id=$subject->father_id");
			$fileDetail['subject_name'] = $subject->subject_name;
			$fileDetail['subject_father_name'] = $subFather->subject_name;
			/*文件语言*/
			$fileLanguage = \FileLanguage::$Language;
			foreach($fileLanguage as $value){
				if($value['id'] == $fileDetail['language']){
					$fileDetail['language_name'] = $value['name'];
				}
			}
			/*发布时间*/
			$pushTime = explode(" ",$fileDetail['addtime']);
			$fileDetail['pushTime'] = $pushTime[0];
			/*文件大小*/
			$fileDetail['file_size_conv'] = $this->fileSizeConv($fileDetail['file_size']);

			//文件评价列表
			$fileDetail['file_comment'] = array();
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
			/*var_dump($fileDetail);
            exit;*/
			$this->view->file = $fileDetail;
		}
	}


	public function filebackupAction()
	{
		if($this->bSignedIn){
			$uid = $this->userInfo->uid;
			$userFiles = Userfile::find(array("uid=$uid and path='/' and ((percent=100 and file_type>".\FileType::FOLDER.") or file_type=".\FileType::FOLDER.")","order"=>"file_type asc,addtime desc,id desc",'limit'=>12,'offset'=>0));
			$this->view->userFiles = $userFiles;
		}
		else{
			$this->response->redirect("/login/login");
		}
	}
	/*回收站*/
	public function trashAction()
	{
		if($this->bSignedIn){

		}
		else{
			$this->response->redirect("/login/login");
		}
	}
	/*下载文件*/
	public function downloadFilesAction()
	{
		do{
			$data = $_POST;
			$file_ids = $data['file_ids'];
			$file_id = implode(',',$file_ids);
			$timestamp = time();
			$auth_token = md5($file_id.'P$&*WIND758U'.$timestamp);
			$dlUrl= "/api/file/downloadFiles?file_ids=$file_id&auth_token=$auth_token&timestamp=$timestamp";
			$this->set_data($dlUrl);
		}while(false);
		$this->output();
	}
	/*下载批量发布的配置文件*/
	public function downloadPushConfigAction(){
		do{
			$post = $_POST;
			$filesArr = $post['file_ids'];
			$files = implode(',',$filesArr);
			$timestamp = time();
			$auth_token = md5($files.'IND&*W75P$8U'.$timestamp);
			$dlUrl = "/api/file/downloadExcel?file_ids=$files&auth_token=$auth_token&timestamp=$timestamp";
			$this->set_data($dlUrl);
		}while(false);
		$this->output();
	}

}