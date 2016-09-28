<?php
namespace Cloud\Live\Controllers;
use Cloud\Models\Classroom;
use Cloud\Models\Device;
use Cloud\Models\Liveuser;
use Cloud\Models\Subject;
use Cloud\Models\Userfile;
use Cloud\Models\Live;
use Cloud\Models\Siteconfig;
use Cloud\Models\Livevideoinfo;
class IndexController extends ControllerBase
{
    public function indexAction()
    {
		$subject = Subject::find(array("father_id=0","order"=>"id asc"));
		$this->view->subjects = $subject;

		//幻灯片
		$siteConfig = Siteconfig::findFirst(array("option_title='site_banner5'"));
		$this->view->images = explode('|',$siteConfig->option_value);
    }
	/*管理后台直播列表*/
	public function listAction()
	{
		if($this->bSignedIn){
			$uid = $this->userInfo->uid;
		}
		else{
			$this->response->redirect("login/login");
		}
	}
	/*直播管理*/
	public function manageAction()
	{
		if($this->bSignedIn){
			$uid = $this->userInfo->uid;
		}
		else{
			$this->response->redirect("login/login");
		}
	}
	/*管理后台发布/编辑直播*/
	public function editAction($id=0)
	{
		if($this->bSignedIn){
			//班级
			$class_room = Classroom::find(array());
			$this->view->class_room = $class_room->toArray();
			if($id){
				$uid = $this->userInfo->uid;
				$detail = Live::findFirst(array("uid=$uid and id=$id"));
				$detail->tags=$detail->tags?explode(',',$detail->tags):'';
				$this->view->detail = $detail;

				$father = Subject::findFirst(array("father_id>0 and id=".$detail->subject_id));
				$this->view->father_id = $father->father_id;
			}
		}
		else{
			$this->response->redirect("login/login");
		}
	}
	/*直播详情页*/
	public function detailAction($id)
	{
		$live = Live::findFirst(array("id=$id"));
		if (!$live)
		{
			$this->response->redirect("login/login");
		}
		if($live->start_time>date('Y-m-d H:i:s'))
		{
			$live->status = 0;
		}
		else if($live->start_time<date('Y-m-d H:i:s')&&$live->end_time>date('Y-m-d H:i:s'))
		{
			$live->status = 1;
		}
		else
		{
			$live->status = 2;
		}

		//相关资料
		$userFileArr = array();
		if(!empty($live->file_ids))
		{
			$userFiles = Userfile::find(array("id in (".$live->file_ids.")"));
			foreach($userFiles as $k=>$userFile)
			{
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
				$userFileArr[$k] = $userFile->toArray();
				$userFileArr[$k]['file_size'] =  $this->fileSizeConv($userFile->file_size);
				$userFileArr[$k]['ext'] =  $type;
			}
		}
		$this->view->relation_file_list = $userFileArr;
		//在线人数
		$liveUserCount = Liveuser::count("live_id=$id");
		$this->view->counter = $liveUserCount;
		$this->view->liveInfo = $live;
		$this->view->liveUser = $live->getUserinfo();

		$subject = Subject::findFirst(array("id=".$live->subject_id));
		$this->view->subject_child = $subject->subject_name;
		$subjectFather = Subject::findFirst(array("id=".$subject->father_id));
		$this->view->subject_father = $subjectFather->subject_name;
	}
	/*
	 * 直播页面
	 * */
	public function playAction($id)
	{
		if($this->bSignedIn){
			if((int)$id<=0){
				$this->response->redirect("login/login");
				return;
			}
			$live = Live::findFirst(array("id=$id"));
			if (!$live)
			{
				$this->response->redirect("login/login");
			}
			if($live->start_time>date('Y-m-d H:i:s'))
			{
				$live->status = 0;
			}
			else if($live->start_time<date('Y-m-d H:i:s')&&$live->end_time>date('Y-m-d H:i:s'))
			{
				$live->status = 1;
			}
			else
			{
				$live->status = 2;
			}
			//相关资料
			$userFileArr = array();
			if(!empty($live->file_ids))
			{
				$userFiles = Userfile::find(array("id in (".$live->file_ids.")"));
				foreach($userFiles as $k=>$userFile)
				{
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
					$userFileArr[$k] = $userFile->toArray();
					$userFileArr[$k]['file_size'] =  $this->fileSizeConv($userFile->file_size);
					$userFileArr[$k]['ext'] =  $type;
				}
			}
			$this->view->relation_file_list = $userFileArr;
			//在线人数
			$liveUserCount = Liveuser::count("live_id=$id");
			$this->view->counter = $liveUserCount;
			$this->view->liveInfo = $live;
			$this->view->liveUser = $live->getUserinfo();

			$subject = Subject::findFirst(array("id=".$live->subject_id));
			$this->view->subject_child = $subject->subject_name;
			$subjectFather = Subject::findFirst(array("id=".$subject->father_id));
			$this->view->subject_father = $subjectFather->subject_name;

			//直播配置
			$this->view->live_config = $this->config->vs2_serv;

			//获取直播的设备名
			$class_room = Classroom::findFirst("id=".$live->class_room_id);
			$device = Device::findFirst(array("id=".$class_room->device_id));
			$this->view->stream_name = $device->stream_name;

			//老师信息
			$this->view->liveUserInfo = $live->getUserInfo()->toArray();
		}
		else{
			$this->response->redirect("login/login?backurl=".$_SERVER['REQUEST_URI']);
		}
	}
	/*录像回看*/
	public function lookbackAction($id=0)
	{
		if($this->bSignedIn){
			if($id<=0)
			{
				$this->response->redirect("index");
				return;
			}
			$live = Live::findFirst(array("id=$id"));
			if(!$live)
			{
				$this->response->redirect("index");
				return;
			}
			$path = '';
			if(!empty($live->video_path))
			{
				$path = substr($live->video_path,0,strlen($live->video_path)-4);
			}
			$this->view->path = $path;
			$this->view->name = $live->name;
			$this->view->id = $live->id;
			$LivevideoinfoList = Livevideoinfo::find(array("type=1 and live_id=".$id,"order"=>"start_time asc"));

			$this->view->knowlegeList = $LivevideoinfoList->toArray();

			$LivevideoinfoList = Livevideoinfo::find(array("type=2 and live_id=".$id,"order"=>"addtime asc"));
			$this->view->qiepianList = $LivevideoinfoList->toArray();
		}
		else
		{
			$this->response->redirect("login/login");
		}
	}
	/*知识点管理*/
	public function topicAction($id=0)
	{
		if($this->bSignedIn)
		{
			$uid = $this->userInfo->uid;
			if($id<=0)
			{
				$this->response->redirect("index");
				return;
			}
			$userFile = Userfile::findFirst(array("id=$id and uid=$uid"));
			if(!$userFile)
			{
				$this->response->redirect("index");
				return;
			}
			$path = '';
			if($userFile->is_video>0)
			{
				$ext = strrchr($userFile->file_name, '.');
				$fname = basename($userFile->file_name,$ext);
				$path = $userFile->uid.$userFile->path.$fname;
			}
			$this->view->path = $path;
			$this->view->live_id = $userFile->is_video;

			$LivevideoinfoList = Livevideoinfo::find(array("type=1 and live_id=".$userFile->is_video,"order"=>"start_time asc"));
			$this->view->knowlegeList = $LivevideoinfoList->toArray();

			$LivevideoinfoList = Livevideoinfo::find(array("type=2 and live_id=".$userFile->is_video,"order"=>"addtime asc"));
			$this->view->qiepianList = $LivevideoinfoList->toArray();
		}
		else{
			$this->response->redirect("login/login");
		}
	}
}