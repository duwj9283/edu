<?php

namespace Cloud\Backend\Controllers;
use Cloud\Models\Files;
use Cloud\Models\User;

class FileController extends ControllerBase
{

    public function checkFilePositionAction()
    {
        $uid = (int)$_GET['uid'];
        $user_token = htmlspecialchars($_GET['user_token']);
        $file_md5 = htmlspecialchars($_GET['file_md5']);

        if($uid<=0 || empty($user_token) || empty($file_md5))
        {
            $response = array('status' => false, 'msg' => '参数错误');
            echo json_encode($response);
            exit;
        }

        $user = User::findFirst($uid);
        if($user)
        {
            if($user_token != $user->user_token)
            {
                $response = array('status' => false, 'msg' => '用户非法');
                echo json_encode($response);
                exit;
            }
        }
        else
        {
            $response = array('status' => false, 'msg' => '用户不存在');
            echo json_encode($response);
            exit;
        }

        $file = Files::findFirst(array("md5='$file_md5'"));
        if($file)
        {
            if($file->percent==100)
            {
                $response = array('status' => false, 'msg' => '文件已经上传成功，无需上传');
                echo json_encode($response);
                exit;
            }
            else
            {
                $response = array('status' => true, 'position' => $file->position);
                echo json_encode($response);
                exit;
            }
        }

        $response = array('status' => true, 'position' => 0);
        echo json_encode($response);
        $this->view->disable();
    }
}

