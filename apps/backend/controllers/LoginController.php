<?php
namespace Cloud\Backend\Controllers;
use Cloud\Models\User;
use Phalcon\Mvc\View;

class LoginController extends ControllerBase
{

    public function indexAction()
    {
        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
    }

    public function doLoginAction()
    {
        $username = htmlspecialchars($_GET['username']);
        $password = htmlspecialchars($_GET['password']);
        $authcode = strtolower(htmlspecialchars($_GET['authcode']));

        $response = array('success' => false, 'error' => '参数错误');
        // 验证验证码是否正确
        if(strtolower($this->session->get('authcode'))==$authcode)
        {
            $code_succ = true;
        }
        else
        {
            $response = array('success' => false, 'error' => '验证码错误');
            $code_succ = false;
        }

        if (!empty($username) && !empty($password) && !empty($authcode) && $code_succ)
        {
            $user = User::findFirst(array("username='$username'"));
            if($user)
            {
                if ($this->security->checkHash($password, $user->password))
                {
                    $user_token = $this->security->getTokenKey();

                    $user->user_token = $user_token;
                    $user->login_count = $user->login_count+1;
                    $user->login_time = date('Y-m-d H:i:s');
                    $user->login_ip = $_SERVER["REMOTE_ADDR"];

                    //更新user_token
                    if(!$user->update())
                    {
                        $response = array('success' => false, 'error' => '登陆错误');
                    }
                    else
                    {
                        //写入seeion
                        $this->session->set('uid',$user->uid);
                        $this->session->set('user_token',$user_token);
                        $response = array('success' => true);
                    }
                }
                else
                {
                    $response = array('success' => false, 'error' => '用户名密码错误');
                }
            }
            else
            {
                $response = array('success' => false, 'error' => '用户名密码错误');
            }
        }
        echo json_encode($response);
        $this->view->disable();
    }
}

