<?php

namespace Cloud\Live\Controllers;

use Cloud\Models\Messagestatus;
use Cloud\Models\User;
use Cloud\Models\Userinfo;
use Cloud\Models\Siteconfig;
use Phalcon\Mvc\Controller;

class ControllerBase extends Controller
{
    protected $responseData = array('code'=>0, 'data'=>array(), 'line'=>__LINE__, 'msg'=>'ok');
	protected $bSignedIn = false;
	protected $userInfo = null;
	protected $msgStatus = 0;

	public function initialize(){
		//加载头部和尾部信息
		$siteConfig = Siteconfig::find(array());
		foreach($siteConfig as $c)
		{
			if($c->id==1)
			{
				$this->view->title = $c->option_value;
			}
			else if($c->id==2)
			{
				$this->view->footerInfo = $c->option_value;
			}
			else if($c->id==3)
			{
				$this->view->logo = $c->option_value;
			}
		}
	}

    public function output()
    {
        echo json_encode($this->responseData);
    }

	public function beforeExecuteRoute(){
		$this->checkSignedIn();
	}

	/**
	 * @brief 登录检查(测试中,默认以10号ID登录)
	 */
//	protected function checkSignedIn(){
//		$this->bSignedIn = false;
//
//		$uid = $this->session->get("uid");
//		$token = $this->session->get("token");
//		if(null == $uid){
//			$uid = 10;
//		}
//		$this->bSignedIn = true;
//		$this->user = User::findFirst($uid);
//		$this->userInfo = Userinfo::findFirst("uid=$uid");
//		$this->view->userInfo = $this->userInfo->toArray();
//		$this->view->user = $this->user->toArray();
//		$this->view->bSignedIn = $this->bSignedIn;
//		return $this->bSignedIn;
//	}

	protected function checkSignedIn(){
		$this->bSignedIn = false;
		$uid = $this->session->get("uid");
		if ($uid) {
			$user = User::findFirst(array('uid=' . $uid));

			$user_token = $this->session->get('user_token');
			if ($user_token == $user->user_token) {
				$this->bSignedIn = true;
				$this->user =$user;
				$this->userInfo = Userinfo::findFirst("uid=$uid");
				$this->view->userInfo = $this->userInfo;
				$this->view->user = $this->user;
				$this->msgStatus = Messagestatus::count(array("receiver_id=$uid and status=1 and view_status=0"));
				$this->view->msgStatus = $this->msgStatus;
			}
		}
		$this->view->bSignedIn = $this->bSignedIn;

		return $this->bSignedIn;
	}

	public function fileSizeConv($size, $unit = 'B', $decimals = 1, $targetUnit = 'auto') {
		$units = array('B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB');
		$theUnit = array_search(strtoupper($unit), $units); //初始单位是哪个
		//判断是否自动计算，
		if ($targetUnit != 'auto')
			$targetUnit = array_search(strtoupper($targetUnit), $units);
		//循环计算
		while ($size >= 1024) {
			$size/=1024;
			$theUnit++;
			if ($theUnit == $targetUnit)//已符合给定则退出循环吧！
				break;
		}

		return sprintf("%1\$.{$decimals}f", $size) . $units[$theUnit];
	}

}
