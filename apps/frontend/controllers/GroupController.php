<?php
/**
 * Created by PhpStorm.
 * User: 20150112
 * Date: 2016/4/11
 * Time: 15:16
 */
namespace Cloud\Frontend\Controllers;

class GroupController extends ControllerBase
{
    public function indexAction()
    {
        //群组前端页面

    }
    public function homeAction()
    {
        //群组主页
        if($this->bSignedIn){
            $uid= $this->user->uid;
        }else{
            $this->response->redirect('/index');
        }

    }
    public function zoneAction()
    {
        //群组空间
        if($this->bSignedIn){
            $uid= $this->user->uid;
        }else{
            $this->response->redirect('/index');
        }
    }
    public function createAction()
    {
        //创建群组
        if($this->bSignedIn){
            $uid= $this->user->uid;
        }else{
            $this->response->redirect('/index');
        }
    }
    /*群组管理*/
    public function manageAction()
    {
        //群组管理
        if($this->bSignedIn){
            $uid= $this->user->uid;
        }else{
            $this->response->redirect('/index');
        }
    }

}