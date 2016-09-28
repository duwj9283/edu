<?php
/**
 * Created by PhpStorm.
 * User: 20150112
 * Date: 2016/3/25
 * Time: 13:51
 */
namespace Cloud\Frontend\Controllers;
use Cloud\Models\Activity;
use Cloud\Models\Activityquestion;

class ActivityController extends ControllerBase
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
    public function detailAction(){
        if($this->bSignedIn){
            $uid = $this->userInfo->uid;
        }
        else{
            $this->response->redirect("/index");
        }
    }
    public function manageAction(){
        if($this->bSignedIn){
            $uid = $this->userInfo->uid;
        }
        else{
            $this->response->redirect("/index");
        }
    }
    public function createAction($id=0){
        if($this->bSignedIn){
            $uid = $this->userInfo->uid;
            if($id){
                $detail =Activity::findFirst(array("id=$id"));
                $questions = Activityquestion::find(['activity_id = ' . $id]);
                $detail->questions = $questions;

            }else{
                $detail =new Activity;
            }
           //print_R($detail->questions);die;
            $this->view->detail = $detail;
        }
        else{
            $this->response->redirect("/index");
        }
    }
}