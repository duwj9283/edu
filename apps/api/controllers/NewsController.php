<?php
namespace Cloud\API\Controllers;
use Cloud\Models\Usernews;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\InclusionIn;
use Phalcon\Validation\Validator\StringLength;
use Phalcon\Mvc\Model\Resultset\Simple as Resultset;
/**
 * @brief 新闻接口
 */
class NewsController extends ControllerBase
{
    /**
     * @brief 添加修改新闻
     * @param id 新闻ID
     * @param title 新闻名称
     * @param content 新闻内容
     * @param status 新闻状态
     */
    public function editNewsAction()
    {
        do {
            $this->validation->add('id', new PresenceOf(array('message'=>'参数缺失:id')));
            $this->validation->add('title', new PresenceOf(array('message'=>'参数缺失:title')));
            $this->validation->add('status', new InclusionIn(array('message'=>"参数值只能为:0/1",
                'domain'=>array('0', '1'),
                'allowEmpty'=>false)));
            $this->validation->add('content', new PresenceOf(array('message'=>'参数缺失:content')));
            $messages = $this->validation->validate($this->params);
            if (count($messages)) {
                foreach ($messages as $message) {
                    array_push($this->errors, strval($message));
                }
                $this->responseData = array("code"=>\Code::ERROR_PARAMS,"msg"=>join(';', $this->errors),"line"=>__LINE__,"data"=>array());
                break;
            }
            if(!$this->checkUserLogin())
            {
                break;
            }
            $uid = $this->uid;
            $id = $this->params['id'];
            $title = $this->params['title'];
            $content = $this->params['content'];
            $status = $this->params['status'];
            $addNewsSql = "INSERT INTO edu_user_news(id,uid,title,content,status) VALUES (".$id.",$uid,'".$title."','".$content."',$status)";
            $duplicate = " ON DUPLICATE KEY UPDATE title=VALUES(title),content=VALUES(content),status=VALUES(status)";
            $add = $this->db->query($addNewsSql.$duplicate);
            if(!$add)
            {
                $this->responseData = array("code"=>\Code::ERROR_DB,"msg"=>'添加失败',"line"=>__LINE__,"data"=>array());
                break;
            }
        }while(false);
        $this->output();
    }
    /**
     * @brief 我的文章列表
     * @param (选填) uid
     * @param (选填) type 默认是0、前台
     * @param (选填）page 当前页 默认是：1
     */
    public function getMyNewsListAction()
    {
        do {
            $uid = isset($this->params['uid'])&&(int)$this->params['uid']>0?$this->params['uid']:0;
            if($uid==0) {
                if ($this->checkUserLogin()) {
                    $uid = $this->uid;
                }
                else
                {
                    break;
                }
            }
            $type = isset($this->params['type'])?(int)$this->params['type']:0;
            $page = isset($this->params['page'])&&(int)$this->params['page']>0?(int)$this->params['page']:1;
            $limit = 24;
            $offset = ($page - 1) * $limit;
            $condition = '';
            if(!$type)
            {
                $condition = " and status=0";
            }
            $userNews = Usernews::find(array("uid=$uid $condition","limit"=>$limit,"offset"=>$offset,"order"=>"addtime desc"));

            $userNewArr = array();
            foreach($userNews as $k=>$userNew)
            {
                $userNewArr[$k] = $userNew->toArray();
                $userNewArr[$k]['content'] = mb_substr(strip_tags($userNew->content),0,50,'utf-8');
            }
            $this->responseData['data']['newsList'] = $userNewArr;
            $newsCounter = Usernews::count(array("uid=$uid $condition"));
            $this->responseData['data']['total'] = $newsCounter;
        }while(false);
        $this->output();
    }

}