<?php
/**
 * Created by PhpStorm.
 * User: 20150112
 * Date: 2016/4/14
 * Time: 16:08
 */
namespace Cloud\Live\Controllers;

class PreviewController extends ControllerBase
{
    public function indexAction()
    {
        if($this->bSignedIn){
            $uid = $this->userInfo->uid;
        }
        else{
            $this->response->redirect("/index");
        }
    }
}