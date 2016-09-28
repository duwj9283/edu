<?php
namespace Cloud\Frontend\Controllers;

class AjaxController extends ControllerBase
{
    public function indexAction()
    {
        $this->$_POST['action']();
    }
    public function login()
    {
        do{
            /*$post = $_POST;
            $userName = $post['username'];
            $passWord = $post['password'];
            $cUrl= \Development::$ROOTPATH.$post['url'];
            $data = array('username'=>$userName,'password'=>$passWord);
            $cbData= $this->curlPost($cUrl,$data);
            $cbDataArray = json_decode($cbData,true);
            $userInfo = $cbDataArray['data']['userInfo'];
            if(!empty($userInfo) AND  $userInfo ){
                $this->session->set('uid',$userInfo['uid']);
                $this->session->set('user_token',$userInfo['user_token']);
            }
            $this->set_output($cbDataArray);*/
        }while(false);
        $this->output();
    }
    public function getAuthCode()
    {
        do{
            /*$post=$_POST;
            $code_type = $post['code_type'];
            $phone = $post['phone'];
            $cUrl = \Development::$ROOTPATH.'/signup/authCode';
            $data = array('phone'=>$phone,'code_type'=>$code_type);
            $cbData = $this->curlPost($cUrl,$data);
            if(!$cbData){
                $cbDataArray = array('code'=>5,'msg'=> "出现错误，请重试",'file'=>__FILE__,'line'=> __LINE__);
                $cbData = json_encode($cbDataArray);
                $this->set_output(json_decode($cbData));
                break;
            }
            $this->set_output(json_decode($cbData));*/
        }while(false);
        $this->output();
    }
    public function registerByPhone()
    {
        do{
            /*$post=$_POST;
            $phone = $post['username'];
            $password = $post['password'];
            $cUrl = \Development::$ROOTPATH.'/signup/byPhone';
            $data = array('phone'=>$phone,'password'=>$password);
            $cbData = $this->curlPost($cUrl,$data);
            $this->set_output(json_decode($cbData));*/
        }while(false);
        $this->output();
    }
}