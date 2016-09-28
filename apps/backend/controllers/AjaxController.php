<?php

namespace Cloud\Backend\Controllers;
use Cloud\Backend\Models\Files;
use Cloud\Backend\Models\Userfiles;
use Phalcon\Mvc\View;

class AjaxController extends ControllerBase
{
    public function filesLogAction()
    {
        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
        $md5 = $_GET['md5'];
        $file = Files::findFirst(array("md5='".$md5."'"));
        $userFiles = array();
        if($file){
            $userFiles = Userfiles::findFirst(array("file_id=".$file->id));
        }
        $this->view->setVar("file",$file);
        $this->view->setVar("user_file",$userFiles);
    }

    public function checkCodeAction()
    {
        $authcode = htmlspecialchars($_GET['authcode']);
        $sesson = $this->session->get('authcode');
        if($sesson==$authcode)
        {
            $response = array('status' => true);
        }
        else
        {
            $response = array('status' => false, 'msg' => '验证码错误');
        }
        echo json_encode($response);
        $this->view->disable();
    }

    function getCheckCodeAction()
    {
        $rank = mt_rand(100000,999999);
        $this->session->set('authcode',$rank);
        echo $rank;
        $this->view->disable();
    }
}

