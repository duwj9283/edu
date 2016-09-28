<?php

namespace Cloud\Backend\Controllers;
use Cloud\Models\Files;
use Cloud\Models\Userfile;
use Phalcon\Mvc\View;


use Phalcon\Mvc\Model\Resultset\Simple as Resultset;

class IndexController extends ControllerBase
{

    public function indexAction()
    {
        $uid = (int)$this->session->get('uid');
        $userFiles = Userfile::find(array("uid=$uid and path='/'",'order'=>'file_type asc,addtime desc,id desc'));
        $this->view->setVar("userFiles",$userFiles);
        $this->view->setVar("userInfo",$this->user_info);
    }
    public function studentAction()
    {
        $uid = (int)$this->session->get('uid');
        $this->view->setVar("userInfo",$this->user_info);
        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
    }
    public function teacherAction()
    {
        $uid = (int)$this->session->get('uid');
        $this->view->setVar("userInfo",$this->user_info);
        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
    }
}

