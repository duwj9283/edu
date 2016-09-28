<?php

namespace Cloud\Frontend\Controllers;
use Cloud\Models\Newsinfo;
use Cloud\Models\Siteconfig;

class NewsController extends ControllerBase
{
    public function indexAction($page=1)
    {
        $keywords = $this->request->get('keywords');
        $condition = '';
        if(isset($keywords)&&!empty($keywords))
        {
            $condition .= " and title like '%$keywords%'";
        }
        $counter = 12;  //默认单次返回的数据数量
        $start = ($page-1)*$counter;
        $news = Newsinfo::find(array("class_id=10011001 and status=1 $condition","order"=>"updated_at desc",'limit'=>$counter,'offset'=>$start));
        $this->view->news = $news->toArray();
        $count = Newsinfo::count(array("class_id=10011001 and status=1 $condition"));
        $this->view->count = $count;
        //幻灯片
        $siteConfig = Siteconfig::findFirst(array("option_title='site_banner7'"));
        $this->view->images = explode('|',$siteConfig->option_value);
    }
}