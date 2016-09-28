<?php
namespace Cloud\API\Controllers;

use Cloud\Models\User;
use Cloud\Models\Userinfo;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\StringLength;

/**
 * @brief 用户登陆
 */
class LoginController extends ControllerBase{

    /**
     * @brief 用户登录
     * @param username（学号/手机号/邮箱）
     * @param password
     */
    public function byUsernameAction()
    {
//        $this->params = array('username'=>'15156027632','password'=>'123456');
        do{
            $this->validation->add('username', new PresenceOf(array('message'=>'参数缺失:username')));
            $this->validation->add("username", new StringLength(array( "max" => 18,
                "min" => 4,
                "messageMaximum" => "长度超出:username",
                "messageMinimum" => "长度不足:username"
            )));

            $this->validation->add('password', new PresenceOf(array('message'=>'参数缺失:password')));
            $this->validation->add("password", new StringLength(array( "max" => 18,
                "min" => 6,
                "messageMaximum" => "长度超出:password",
                "messageMinimum" => "长度不足:password"
            )));
            $messages = $this->validation->validate($this->params);
            if( count($messages) ){
                foreach($messages as $message) {
                    array_push($this->errors, strval($message));
                }
                $this->responseData = array("code"=>\Code::ERROR_PARAMS,"msg"=>join(';', $this->errors),"line"=>__LINE__,"data"=>array());
                break;
            }
            $username = $this->params['username'];
            $password = $this->params['password'];
            $where = array("username='$username'");
//            if(strpos($username, "@")){
//                $where = array("email='$username'");
//            }elseif(is_numeric($username) && preg_match('/^(1[3|4|5|7|8])\d{9}$/',$username)){
//                $where = array("phone='$username'");
//            }else{
//                $where = array("username='$username'");
//            }
            $user = User::findFirst($where);
            if(!$user)
            {
                $this->responseData = array("code"=>\Code::ERROR,"msg"=>"用户不存在","line"=>__LINE__,"data"=>array());
                break;
            }
            $postPassword = sha1(md5($password)."sdkjf*^#HRGF*");
            if ($postPassword!=$user->password)
            {
                $this->responseData = array("code"=>\Code::ERROR,"msg"=>"帐号或者密码不正确","line"=>__LINE__,"data"=>array());
                break;
            }
//            if (!$this->security->checkHash($password, $user->password))
//            {
//                $this->responseData = array("code"=>\Code::ERROR,"msg"=>"帐号或者密码不正确","line"=>__LINE__,"data"=>array());
//                break;
//            }
            $user_token = $this->security->getTokenKey();
            $user->user_token = $user_token;
            $user->login_count = $user->login_count+1;
            $user->login_ip = $_SERVER["REMOTE_ADDR"];
            $user->login_time = date('Y-m-d H:i:s', time());

            //更新user_token
            if(!$user->update())
            {
                $this->responseData = array("code"=>\Code::ERROR,"msg"=>"登陆失败","line"=>__LINE__,"data"=>array());
                break;
            }

            //写入seeion
            $this->session->set('uid',$user->uid);
            $this->session->set('user_token',$user_token);

            $data = $this->initUser($user->uid);
            $this->responseData['data']['userInfo'] = $data;
        }while(false);
        $this->output();
    }


    /**
     * @brief   登录成功后初始化用户登录所需要的数据
     */
    private function initUser($uid)
    {
        $user = User::findFirst("uid=$uid");
        $userInfo = Userinfo::findFirst("uid=$uid");
        return array_merge($user->toArray(),$userInfo->toArray());
    }

    /**
     * @brief  退出登录
     */
    public function signoutAction()
    {
        do{
            $this->session->remove("uid");
            $this->session->remove("user_token");
        }while(false);
        $this->output();
    }
}
