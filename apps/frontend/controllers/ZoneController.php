<?php
/**
 * Created by PhpStorm.
 * User: 20150112
 * Date: 2016/4/11
 * Time: 15:16
 */
namespace Cloud\Frontend\Controllers;
use Cloud\Models\Subject;
use Cloud\Models\Userinfo;
use Cloud\Models\Userfollow;
use Cloud\Models\Siteconfig;

class ZoneController extends ControllerBase
{
    public function indexAction()
    {
        //幻灯片
        $siteConfig = Siteconfig::findFirst(array("option_title='site_banner6'"));
        $this->view->images = explode('|',$siteConfig->option_value);

        $keywords = $this->request->get('keywords');
        $keywords = isset($keywords)?$keywords:'';
        $page = 1;
        $limit = 12;
        $offset = ($page - 1) * $limit;
        $condition = "";
        if(!empty($keywords))
        {
            $condition .= "nick_name like '%$keywords%' and ";
        }
        $userInfos = UserInfo::find(array("$condition role_id=2","limit"=>$limit,"offset"=>$offset));
        $userInfoArr = array();
        foreach($userInfos as $k=>$userInfo)
        {
            $userInfoArr[$k]['userInfo'] = $userInfo->toArray();
            //计算关注人数
            $followCounter = Userfollow::count(array("tuid=".$userInfo->uid));
            $userInfoArr[$k]['userInfo']['followCount'] = $followCounter;
        }
        $this->view->userInfoList = $userInfoArr;
        $counter = UserInfo::count(array("$condition role_id=2"));
        $this->view->total = $counter;
        $subjects = Subject::find(array("father_id=0 and visible=1"));
        $this->view->subjects = $subjects->toArray();
    }
}