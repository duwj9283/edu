<?php

namespace Cloud\Backend\Controllers;

use Phalcon\Mvc\Controller;
use Cloud\Models\User;
use Cloud\Models\Userinfo;

class ControllerBase extends Controller
{
    protected $user_info = array();

    protected function curlPost($url, $data, $option=array()){

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data) );
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

        if( !empty($options)){
            curl_setopt_array($ch, $options);
        }
        $results = curl_exec($ch);

        if( !$results ){
            var_dump(curl_error($ch));
            return false;
        }
        curl_close($ch);

        return $results;
    }

    public function beforeExecuteRoute(){
        $uid = $this->session->get('uid');
        $user_token = $this->session->get('user_token');
        $request_url = explode('?',$_SERVER["REQUEST_URI"]);

        //判断是否是登录状态
        if(!empty($uid)&&!empty($user_token))
        {
            //判断是否是无需登录页面，是：跳转到首页
            if(in_array($request_url[0],$this->noNeedLoginUrl()))
            {
                header("Location:/backend");
                exit;
            }
            //用户信息
            $user = User::findFirst(array("uid=$uid"));
            if($user&&$user_token==$user->user_token)
            {
                $user_info = Userinfo::findFirst("uid=$uid");
                $this->user_info = array_merge($user->toArray(),$user_info->toArray());
            }
            else
            {
                $this->session->set('uid','');
                $this->session->set('user_token','');
                header("Location:/backend/login");
                exit;
            }
        }
        else
        {
            //判断是否是无需登录页面，是：跳转当前页面
            if(!in_array($request_url[0],$this->noNeedLoginUrl()))
            {
                header("Location:/backend/login");
                exit;
            }
        }
    }

    private function noNeedLoginUrl()
    {
        return array(
            '/backend/login',
            '/backend/login/doLogin',
            '/backend/captcha',
            '/backend/ajax/checkCode',
            '/backend/ajax/getCheckCode'
        );
    }
}
