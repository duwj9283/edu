<?php

namespace Cloud\Backend\Controllers;
use Cloud\Models\Livelevel1types;
use Cloud\Models\Files;
use Cloud\Models\Livelevel2types;
use Cloud\Models\Userfile;
use Phalcon\Mvc\View;


use Phalcon\Mvc\Model\Resultset\Simple as Resultset;

class LiveController extends ControllerBase
{
	public function initialize(){
		$this->view->title = "直播管理";
	}

    public function indexAction()
    {
        $uid = (int)$this->session->get('uid');
        $userFiles = Userfile::find(array("uid=$uid and path='/'",'order'=>'file_type asc,addtime desc,id desc'));

        $this->view->setVar("userFiles",$userFiles);

        $this->view->setVar("userInfo",$this->user_info);
    }

	/**
	 * @brief 一级菜单管理
	 */
	public function level1TypeAction(){

		$types = Livelevel1types::find();

		$this->view->types = $types;
		$this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
	}

	/**
	 * @brief 二级菜单管理
	 */
	public function level2TypeAction(){
		$types = Livelevel2types::find();
		$this->view->types = $types;
		$this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
	}
}

