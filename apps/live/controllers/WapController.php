<?php
namespace Cloud\Live\Controllers;

use Cloud\Models\Classroom;
use Cloud\Models\Device;
use Cloud\Models\Liveuser;
use Cloud\Models\Subject;
use Cloud\Models\Userfile;
use Cloud\Models\Live;

class WapController extends ControllerBase
{
    public function indexAction($id)
    {
		if((int)$id<=0){
			$this->response->redirect("/index");
			return;
		}
		$live = Live::findFirst(array("id=$id"));
		if (!$live)
		{
			$this->response->redirect("/index");
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
		$this->view->liveUserInfo = $live->getUserInfo();

    }
	/*管理后台直播列表*/
	public function listAction()
	{
		if($this->bSignedIn){
			$uid = $this->userInfo->uid;
		}
		else{
			$this->response->redirect("/index");
		}
	}
	/*直播管理*/
	public function manageAction()
	{
		if($this->bSignedIn){
			$uid = $this->userInfo->uid;
		}
		else{
			$this->response->redirect("/index");
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
			$this->response->redirect("/index");
		}
	}
	/*直播详情页*/
	public function detailAction($id)
	{
		if($this->bSignedIn){
			if((int)$id<=0){
				$this->response->redirect("/index");
				return;
			}
			$live = Live::findFirst(array("id=$id"));
			if (!$live)
			{
				$this->response->redirect("/index");
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
		else{
			$this->response->redirect("/index");
		}
	}
	/*
	 * 直播页面
	 * */
	public function playAction($id)
	{
		if($this->bSignedIn){
			if((int)$id<=0){
				$this->response->redirect("/index");
				return;
			}
			$live = Live::findFirst(array("id=$id"));
			if (!$live)
			{
				$this->response->redirect("/index");
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
		}
		else{
			$this->response->redirect("/index");
		}

	}
}