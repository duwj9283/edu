<?php
namespace Cloud\Backend\Controllers;

class LogoutController extends ControllerBase
{
    public function indexAction()
    {
        $this->session->set('uid','');
        $this->session->set('user_token','');
        header("Location:/backend/login");
    }
}

