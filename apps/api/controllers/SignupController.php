<?php
namespace Cloud\API\Controllers;

use Cloud\Models\User;
use Cloud\Models\Userinfo;
use Cloud\Models\Smsrecord;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\StringLength;
use Phalcon\Validation\Validator\InclusionIn;
/**
 * @brief 用户注册
 */
class SignupController extends ControllerBase
{
    /**
     * @brief 用户名登陆
     * @param username
     */
    public function byUsernameAction()
    {
        do{
            $this->validation->add('username', new PresenceOf(array('message'=>'参数缺失:username')));
            $this->validation->add('password', new PresenceOf(array('message'=>'参数缺失:password')));
            $messages = $this->validation->validate($this->params);
            if( count($messages) )
            {
                foreach($messages as $message)
                {
                    array_push($this->errors, strval($message));
                }
                $this->responseData = array("code"=>\Code::ERROR_PARAMS,"msg"=>join(';', $this->errors),"line"=>__LINE__,"data"=>array());
                break;
            }
            $username = $this->params['username'];
            $password = $this->params['password'];
            $user = User::findFirst(array( "username = '$username'" ));
            //  检查手机号是否存在
            if( $user)
            {
                $this->responseData = array("code"=>\Code::ERROR,"msg"=>"该账号已经注册","line"=>__LINE__,"data"=>array());
                break;
            }
            $user = new User();
            $user->username = $username;
            $user->user_token = $this->security->getTokenKey();
            $user->login_time = date('Y-m-d H:i:s',time());
//            $user->password = $this->security->Hash($password);
            $user->password = sha1(md5($password)."sdkjf*^#HRGF*");
            if(!$this->signup($user))
            {
                $this->responseData = array("code"=>\Code::ERROR,"msg"=>"注册失败","line"=>__LINE__,"data"=>array());
                break;
            }
            //写入seeion
            $this->session->set('uid',$user->uid);
            $this->session->set('user_token',$user->user_token);
        }while(false);
        $this->output();
    }

    /**
     * @brief 手机号码注册
     * @param phone 手机号(11位)
     * @param password  登陆密码
     * @param code 短信验证码
     */
    public function byPhoneAction()
    {
        do{
            $this->validation->add('phone', new PresenceOf(array('message'=>'参数缺失:phone')));
            $this->validation->add('code', new PresenceOf(array('message'=>'参数缺失:code')));
            $this->validation->add('password', new PresenceOf(array('message'=>'参数缺失:password')));
            $this->validation->add("phone", new StringLength(array( "max" => 11,
                "min" => 11,
                "messageMaximum" => "长度超出:phone",
                "messageMinimum" => "长度不足:phone"
            )));
            $messages = $this->validation->validate($this->params);
            if( count($messages) )
            {
                foreach($messages as $message)
                {
                    array_push($this->errors, strval($message));
                }
                $this->responseData = array("code"=>\Code::ERROR_PARAMS,"msg"=>join(';', $this->errors),"line"=>__LINE__,"data"=>array());
                break;
            }
            $phone = $this->params['phone'];
            $password = $this->params['password'];
            $code = $this->params['code'];
            $cache_key = "EDU:SIGNUP:$phone";
            //  检查验证码是否正确
            $redis_code = $this->redis->get($cache_key);
            if( $code != $redis_code )
            {
                $this->responseData = array("code"=>\Code::ERROR,"msg"=>"验证码错误","line"=>__LINE__,"data"=>array());
                break;
            }
            $user = User::findFirst(array( "phone = $phone" ));
            //  检查手机号是否存在
            if( $user)
            {
                $this->responseData = array("code"=>\Code::ERROR,"msg"=>"该手机号已经注册,请直接登录","line"=>__LINE__,"data"=>array());
                break;
            }
            $user = new User();
            $user->username = $phone;
            $user->user_token = $this->security->getTokenKey();
            $user->phone = $phone;
            $user->login_time = date('Y-m-d H:i:s',time());
            $user->password = $this->security->Hash($password);
            if(!$this->signup($user))
            {
                $this->responseData = array("code"=>\Code::ERROR,"msg"=>"注册失败","line"=>__LINE__,"data"=>array());
                break;
            }
            //写入seeion
            $this->session->set('uid',$user->uid);
            $this->session->set('user_token',$user->user_token);
        }while(false);
        $this->output();
    }

    private function signup($user)
    {
        $state = true;
        $this->db->begin();
        if( !$user->save() ) {
            $this->db->rollback();
            $state = false;
        }
        $userInfo = new Userinfo();
        $uid = $user->uid;
        $userInfo->uid = $uid;
        if( !$userInfo->save() ) {
            $this->db->rollback();
            $state = false;
        }
        $dir = $this->config->upload_path.$uid;
        if (!file_exists($dir))
        {
            mkdir($dir,0755);
            chmod($dir,0755);
        }
        $this->db->commit();
        return $state;
    }

    /**
     * 注: 缓存的格式为 {EDU:PASSWORD::TYPE:PHONE}
     *      CHANGE_PHONE 更新用户手机号
     * @brief 获取手机验证码
     * @param phone 手机号
     * @param code_type 验证码类型 {FORGET:SIGNUP}
     */
    public function authCodeAction()
    {
//        $this->params = array("phone"=>"15156027632","code_type"=>"SIGNUP");
        do{
            $this->validation->add('phone', new PresenceOf(array('message'=>'参数缺失:phone')));
            $this->validation->add('code_type', new InclusionIn(array('message'=>"参数值只能为:FORGOT 或者 SIGNUP or BIND",
                'domain'=>array('FORGOT', 'SIGNUP', 'BIND', 'CHANGE_PHONE'),
                'allowEmpty'=>false)));
            $messages = $this->validation->validate($this->params);
            if( count($messages) ){
                foreach($messages as $message) {
                    array_push($this->errors, strval($message));
                }
                $this->responseData = array("code"=>\Code::ERROR_PARAMS,"msg"=>join(';', $this->errors),"line"=>__LINE__,"data"=>array());
                break;
            }

            $code_type = $this->params['code_type'];
            $phone = $this->params['phone'];
            //  缓存
            $cache_key = "EDU:$code_type:$phone";
            //  缓存有效期(秒)
            $ttl = 86400;
            //  检查手机是否已经注册
            $user = User::findFirst(array("phone='$phone'"));
            if( $code_type == "SIGNUP" AND $user ){
                $this->responseData = array("code"=>\Code::ERROR,"msg"=>"该手机号已经注册平台，请直接登录!","line"=>__LINE__,"data"=>array());
                break;
            }
            else if($code_type == "FORGET" AND !$user){
                $this->responseData = array("code"=>\Code::ERROR,"msg"=>"该手机号还没有注册，请直接注册!","line"=>__LINE__,"data"=>array());
                break;
            }
            else if($code_type == "BIND" AND $user){
                //  绑定手机号
                $this->responseData = array("code"=>\Code::ERROR,"msg"=>"该手机号已经注册平台，不能绑定!","line"=>__LINE__,"data"=>array());
                break;
            }
            //  检查是否已经过了一分钟还没收到
            $cur_ttl = $this->redis->ttl($cache_key);
            $time_passed = $ttl - $cur_ttl;
            if( $time_passed < 60 ){
                $resend_limit_seconds = 60 - $time_passed;
                $this->responseData = array("code"=>\Code::ERROR,"msg"=>"请稍等>>${resend_limit_seconds}<<秒后再次尝试","line"=>__LINE__,"data"=>array());
                break;
            }
            $code = $this->redis->get($cache_key);
            if( !$code )
            {
                $code = rand(100001,999999);
            }
            $smsInfo = $this->config->sms;
            $postData = 'uid='.$smsInfo->uid.
                '&pas='.$smsInfo->password.
                '&mob='.$phone.
                '&cid=UT9PFxOIlRyY'.
                '&p1='.$code.
                '&type=json';

            $results = $this->curlPost($smsInfo->url, $postData);
            $retData = json_decode($results,true);
            if( $retData['code']==0)
            {
                $smsRecord = new Smsrecord();
                $smsRecord->phone = $phone;
                $smsRecord->message = $code;
                $smsRecord->create_time = time();
                if(!$smsRecord->save()){
                    $this->responseData = array("code"=>\Code::ERROR,"msg"=>"记录出错","line"=>__LINE__,"data"=>array());
                    break;
                }
                $bOK = $this->redis->setex($cache_key, $ttl, $code);
                if( !$bOK)
                {
                    $this->responseData = array("code"=>\Code::ERROR,"msg"=>"缓存出错","line"=>__LINE__,"data"=>array());
                    break;
                }
            }
            else
            {
                $this->responseData = array("code"=>\Code::ERROR,"msg"=>"发送密码错误","line"=>__LINE__,"data"=>array());
                break;
            }
        }while(false);
        $this->output();
    }

    /**
     * @brief 忘记密码
     * @param username
     */
    public function forgetPasswordAction()
    {
        do{
            $this->validation->add('username', new PresenceOf(array('message'=>'参数缺失:username')));
            $messages = $this->validation->validate($this->params);
            if( count($messages) )
            {
                foreach($messages as $message)
                {
                    array_push($this->errors, strval($message));
                }
                $this->responseData = array("code"=>\Code::ERROR_PARAMS,"msg"=>join(';', $this->errors),"line"=>__LINE__,"data"=>array());
                break;
            }
            $username = $this->params['username'];
            $user = User::findFirst(array( "username = '$username'" ));
            if(!$user)
            {
                $this->responseData = array("code"=>\Code::ERROR,"msg"=>"无效的用户名","line"=>__LINE__,"data"=>array());
                break;
            }
            $user->is_forget = 1;
            if(!$user->update())
            {
                $this->responseData = array("code"=>\Code::ERROR,"msg"=>"密码重置失败","line"=>__LINE__,"data"=>array());
                break;
            }
        }while(false);
        $this->output();
    }

    /**
     * @brief 验证用户名
     * @param username
     */
    public function checkUsernameAction()
    {
        do{
            $this->validation->add('username', new PresenceOf(array('message'=>'参数缺失:username')));
            $messages = $this->validation->validate($this->params);
            if( count($messages) )
            {
                foreach($messages as $message)
                {
                    array_push($this->errors, strval($message));
                }
                $this->responseData = array("code"=>\Code::ERROR_PARAMS,"msg"=>join(';', $this->errors),"line"=>__LINE__,"data"=>array());
                break;
            }
            $username = $this->params['username'];
            $user = User::findFirst(array( "username = '$username'" ));
            //  检查用户名是否存在
            if($user)
            {
                $this->responseData = array("code"=>\Code::ERROR,"msg"=>"用户名已存在","line"=>__LINE__,"data"=>array());
                break;
            }
        }while(false);
        $this->output();
    }
}