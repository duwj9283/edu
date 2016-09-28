<?php
/**
 * Created by PhpStorm.
 * User: 20150112
 * Date: 2016/3/25
 * Time: 13:14
 */
namespace Cloud\Frontend\Controllers;

class CamtasiastudioController extends ControllerBase
{
    public function indexAction(){

        if($this->bSignedIn){
            $uid = $this->userInfo->uid;
        }
        else{
            $this->response->redirect("/index");
        }
    }
    public function detailAction(){

        if($this->bSignedIn){
            $uid = $this->userInfo->uid;
        }
        else{
            $this->response->redirect("/index");
        }
    }
}