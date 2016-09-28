<?php
/**
 * Created by PhpStorm.
 * User: 20150112
 * Date: 2016/3/7
 * Time: 14:31
 */
namespace Cloud\Frontend\Controllers;

class LoginController extends ControllerBase
{
    public function indexAction(){

    }
    public function loginAction(){
        if($this->bSignedIn) {
            $this->response->redirect('/index');
        }
    }
    public function registerAction(){
        if($this->bSignedIn) {
            $this->response->redirect('/index');
        }
    }
    public function registerNextAction(){

        $class=['一班','二班','三班','四班','五班','六班','七班','八班'];//班级
        $this->view->classArr = $class;
        $this->view->pick("login/register-next");
    }
    public function forgetAction(){
        if($this->bSignedIn) {
            $this->response->redirect('/index');
        }
    }

    /**
     * @brief  退出登录
     */
    public function signoutAction()
    {
        do{
            $this->session->remove("uid");
            $this->session->remove("user_token");
            $this->response->redirect('/index');

        }while(false);


    }
}