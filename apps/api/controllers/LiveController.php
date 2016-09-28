<?php
namespace Cloud\API\Controllers;

use Cloud\Models\Live;
use Cloud\Models\Liveuser;
use Cloud\Models\Userfile;
use Cloud\Models\Userfileshare;
use Phalcon\Validation\Validator\InclusionIn;
use Phalcon\Validation\Validator\PresenceOf;

class LiveController extends ControllerBase
{
    /**
     * @brief 一级菜单管理
     */
    public function updateLevel1TypeAction()
    {
        $types = Livelevel1types::find();
    }

    /**
     * @brief 二级菜单管理
     */
    public function updateLevel2TypeAction()
    {
        $types = Livelevel2types::find();
    }

    /**
     * @brief 获取所有公开的直播列表
     * @param page (选填) 当前页 默认是：1
     * @param status 状态 0、全部 1、即将开始 2、直播中  3、已结束
     * @param (选填) keywords 关键词
     */
    public function getPubListAction()
    {
        do {
            $page = isset($this->params['page'])&&(int)$this->params['page']>0?$this->params['page']:1;
            $status = isset($this->params['status'])&&(int)$this->params['status']>0?$this->params['status']:0;
            $keywords = isset($this->params['keywords'])?$this->params['keywords'] : '';
            $counter = 12;  //默认单次返回的数据数量
            $start = ($page-1)*$counter;
            $condition = '';
            $nowTime = date('Y-m-d H:i:s');
            if($status==1)
            {
                $condition .= " and start_time > '$nowTime'";
            }
            if($status==2)
            {
                $condition .= " and start_time < '$nowTime' and end_time>'$nowTime'";
            }
            if($status==3)
            {
                $condition .= " and end_time < '$nowTime'";
            }
            if($keywords)
            {
                $condition .= " and name like '%$keywords%'";
            }
            $lives = Live::find(array("publish_type=1 $condition","order"=>"updated_time desc",'limit'=>$counter,'offset'=>$start));
            $liveArr = array();
            foreach ($lives as $live) {
                $liveArr[$live->id] = $live->toArray();
                if($live->start_time>date('Y-m-d H:i:s'))
                {
                    $liveArr[$live->id]['live_status'] = 0;
                }
                else if($live->start_time<date('Y-m-d H:i:s')&&$live->end_time>date('Y-m-d H:i:s'))
                {
                    $liveArr[$live->id]['live_status'] = 1;
                }
                else
                {
                    $liveArr[$live->id]['live_status'] = 2;
                }
                $liveArr[$live->id]['userInfo'] = $live->getUserinfo();
            }
            $total = Live::count(array("publish_type=1 $condition"));
            $this->responseData['data']['total'] = $total;
            $this->responseData['data']['publicLiveList'] = array_values($liveArr);
        } while (false);
        $this->output();
    }

    /**
     * @brief 获取个人创建的直播列表
     * @param page (选填) 当前页 默认是：1
     */
    public function getMyListAction()
    {
        do {
            if (!$this->checkUserLogin()) {
                break;
            }
            $uid = $this->uid;
            $page = isset($this->params['page'])&&(int)$this->params['page']>0?$this->params['page']:1;
            $limit = 12;
            $offset = ($page-1)*$limit;
            $rows = Live::find(array("uid=$uid","order"=>"start_time desc","offset"=>$offset,"limit"=>$limit));
            $data = [];
            foreach ($rows as $row) {
                if($row->start_time>date('Y-m-d H:i:s'))
                {
                    $live_status = 0;
                }
                else if($row->start_time<date('Y-m-d H:i:s')&&$row->end_time>date('Y-m-d H:i:s'))
                {
                    $live_status = 1;
                }
                else
                {
                    $live_status = 2;
                }
                $data[] = [
                    'id' => $row->id,
                    'name' => $row->name,
                    'intro' => $row->intro,
                    'content' => $row->content,
                    'cover_pic' => $row->cover_pic,
                    'tags' => explode(',', $row->tags),
                    'start_time' => $row->start_time,
                    'end_time' => $row->end_time,
                    'publish_type' => $row->publish_type,
                    'group_id' => $row->group_id,
                    'visit_pass' => $row->visit_pass,
                    'is_record' => $row->is_record,
                    'allow_chat' => $row->allow_chat,
                    'users_limit' => $row->users_limit,
                    'is_charge' => $row->is_charge,
                    'cost' => $row->cost,
                    'device_by' => $row->device_by,
                    'live_status' => $live_status,
                    'created_time' => $row->created_time,
                    'class_room_id' => $row->class_room_id
                ];
            }
            $this->responseData['data']['rows'] = $data;
            $count = Live::count(array("uid=$uid"));
            $this->responseData['data']['count'] = $count;
        } while (false);
        $this->output();
    }

    /**
     * @brief 根据日期获取个人创建的直播列表
     */
    public function getMyListByDateAction()
    {
        do {
            if (!$this->checkUserLogin()) {
                break;
            }
            $uid = $this->uid;
            $rows = Live::find(['uid=' . $uid, 'order' => "start_time DESC"]);
            $data = [];
            foreach ($rows as $row) {
                $k = date('Y-m-d', strtotime($row->start_time));
                $data[$k] = [];
                array_push($data[$k], [
                    'id' => $row->id,
                    'name' => $row->name,
                    'intro' => $row->intro,
                    'content' => $row->content,
                    'cover_pic' => $row->cover_pic,
                    'tags' => explode(',', $row->tags),
                    'start_time' => $row->start_time,
                    'end_time' => $row->end_time,
                    'publish_type' => $row->publish_type,
                    'group_id' => $row->group_id,
                    'visit_pass' => $row->visit_pass,
                    'is_record' => $row->is_record,
                    'allow_chat' => $row->allow_chat,
                    'users_limit' => $row->users_limit,
                    'is_charge' => $row->is_charge,
                    'cost' => $row->cost,
                    'device_by' => $row->device_by,
                    'created_time' => $row->created_time
                ]);
            }
            $this->responseData['data']['rows'] = $data;
        } while (false);
        $this->output();
    }

    /**
     * @brief 获取直播信息
     * @param [必填] id 直播ID
     */
    public function getInfoAction()
    {
        do {
            $this->validation->add('id', new PresenceOf(['message' => '参数缺失:id']));
            $messages = $this->validation->validate($this->params);
            if (count($messages)) {
                foreach ($messages as $message) {
                    array_push($this->errors, strval($message));
                }
                $this->responseData = ['code' => \Code::ERROR_PARAMS, 'msg' => join(';', $this->errors), 'line' => __LINE__, 'data' => []];
                break;
            }
            if (!$this->checkUserLogin()) {
                break;
            }
            $uid = $this->uid;
            $id = intval($this->params['id']);
            $row = Live::findFirst(['uid=' . $uid, 'id=' . $id]);
            if (!$row) {
                $this->responseData = ['code' => \Code::ERROR_PARAMS, 'msg' => '课程不存在', 'line' => __LINE__, 'data' => []];
                break;
            }
            $data = [
                'id' => $row->id,
                'name' => $row->name,
                'intro' => $row->intro,
                'content' => $row->content,
                'cover_pic' => $row->cover_pic,
                'tags' => explode(',', $row->tags),
                'start_time' => $row->start_time,
                'end_time' => $row->end_time,
                'publish_type' => $row->publish_type,
                'group_id' => $row->group_id,
                'visit_pass' => $row->visit_pass,
                'is_record' => $row->is_record,
                'allow_chat' => $row->allow_chat,
                'users_limit' => $row->users_limit,
                'is_charge' => $row->is_charge,
                'cost' => $row->cost,
                'device_by' => $row->device_by,
            ];
            $this->responseData['data'] = $data;
        } while (false);
        $this->output();
    }

    /**
     * @brief 添加直播
     * @param [必填] name 直播名称
     * @param [必填] start_time 开始时间
     * @param [必填] end_time 开始时间
     * @param [必填] publish_type 发布范围: 1：公开 2：群组 3：私有（含密码）
     * @param [必填] is_record 是否录制: 0:不录制,1:录制
     * @param [必填] allow_chat 聊天设置: 0:允许聊天,1:禁止聊天
     * @param [必填] is_charge 是否收费 0:免费,1:收费
     * @param [必填] device_by 关联设备 1:手机直播,2:屏幕直播,3:硬件终端直播
     * @param [必填]subject_id 二级学科ID
     * @param [必填]class_room_id 教室ID
     * @param cost 收费价格 0:免费
     * @param users_limit 人数限制 0:不限制
     * @param intro 简介
     * @param content 内容
     * @param cover_pic 封面
     * @param tags 标签（多个标签之间用英文逗号间隔)
     * @param group_id 发布到群组Id
     * @param visit_pass 私有口令
     * @param subject_id 二级学科ID
     * @param class_room_id 教室ID
     * @param（选填）file_ids 相关资料
     */
    public function createAction()
    {
        do {
            $this->validation->add('name', new PresenceOf(['message' => '参数缺失:name']));
            $this->validation->add('start_time', new PresenceOf(['message' => '参数缺失:start_time']));
            $this->validation->add('end_time', new PresenceOf(['message' => '参数缺失:end_time']));
            $this->validation->add('publish_type', new InclusionIn([
                'message' => '参数type值只能为:1/2/3',
                'domain' => ['1', '2', '3'],
                'allowEmpty' => false,
            ]));
            $this->validation->add('allow_chat', new InclusionIn([
                'message' => '参数allow_chat值只能为:0/1',
                'domain' => ['0', '1'],
                'allowEmpty' => false,
            ]));
            $messages = $this->validation->validate($this->params);
            if (count($messages)) {
                foreach ($messages as $message) {
                    array_push($this->errors, strval($message));
                }
                $this->responseData = ['code' => \Code::ERROR_PARAMS, 'msg' => join(';', $this->errors), 'line' => __LINE__, 'data' => []];
                break;
            }

            if (!$this->checkUserLogin()) {
                break;
            }
            $uid = $this->uid;
            $name = strval($this->params['name']);
            $intro = strval($this->params['intro']);
            $content = strval($this->params['content']);
            $cover_pic = strval($this->params['cover_pic']);
            $tags = isset($this->params['tags']) ?: '';
            $start_time = strval($this->params['start_time']);
            $end_time = strval($this->params['end_time']);
            $publish_type = intval($this->params['publish_type']);
            $group_id = intval($this->params['group_id']);
            $visit_pass = strval($this->params['visit_pass']);
            $is_record = 1;
            $allow_chat = intval($this->params['allow_chat']);
            $users_limit = intval($this->params['users_limit']);
            $is_charge = 0;
            $cost = 0;
            $device_by = 3;
            $subject_id = intval($this->params['subject_id']);
            $class_room_id = intval($this->params['class_room_id']);
            $tags = is_array($tags) ? implode(',', $tags) : strval($tags);
            $file_ids = isset($this->params['file_ids'])?$this->params['file_ids']:'';
            $created_time = $updated_time = date('Y-m-d H:i:s');
            if(!empty($file_ids))
            {
                $userFiles = Userfile::find(array("id in(".$file_ids.") and uid=$uid"));
                foreach($userFiles as $userFile)
                {
                    $userFileShare = Userfileshare::findFirst(array("user_file_id=".$userFile->id." and uid=$uid"));
                    if(!$userFileShare)
                    {
                        $share = new Userfileshare();
                        $share->user_file_id = $userFile->id;
                        $share->uid = $uid;
                        $share->status = 0;
                        $share->addtime = $created_time;
                        $share->create();
                    }
                }
            }
            $data = compact('uid', 'name', 'intro', 'content', 'cover_pic', 'tags', 'start_time','end_time', 'publish_type', 'group_id', 'visit_pass', 'is_record', 'allow_chat', 'users_limit', 'is_charge', 'cost', 'device_by', 'created_time', 'updated_time','subject_id','class_room_id','file_ids');
            $arow = new Live;
            foreach ($data as $k => $v) {
                $arow->$k = $v;
            }
            if ($arow->create()) {
                $live_id = $arow->id;
                $this->responseData['data']['id'] = $live_id;
                break;
            }
            $this->responseData = [
                'code' => \Code::ERROR_PARAMS,
                'msg' => '课程添加失败',
                'line' => __LINE__,
                'data' => [],
            ];
        } while (false);
        $this->output();
    }

    /**
     * @brief 修改直播
     * @param [必填] id 直播Id
     * @param status 直播状态 0:未开始,1:进行中,2:已结束
     */
    public function updateAction()
    {
        do {
            if (!$this->checkUserLogin()) {
                break;
            }
            $uid = $this->uid;

            $id = intval($this->params['id']);
            $row = Live::findFirst(['id=' . $id, 'uid=' . $uid]);
            if (empty($row)) {
                $this->responseData = ['code' => \Code::ERROR, 'msg' => '直播不存在', 'line' => __LINE__, 'data' => []];
                break;
            }
            if (isset($this->params['name'])) {
                $row->name = $this->params['name'];
            }
            if (isset($this->params['intro'])) {
                $row->intro = $this->params['intro'];
            }
            if (isset($this->params['content'])) {
                $row->content = $this->params['content'];
            }
            if (isset($this->params['cover_pic'])) {
                $row->cover_pic = $this->params['cover_pic'];
            }
            if (isset($this->params['start_time'])) {
                $row->start_time = $this->params['start_time'];
            }
            if (isset($this->params['end_time'])) {
                $row->end_time = $this->params['end_time'];
            }
            if (isset($this->params['publish_type'])) {
                $row->publish_type = $this->params['publish_type'];
            }
            if (isset($this->params['group_id'])) {
                $row->group_id = $this->params['group_id'];
            }
            if (isset($this->params['visit_pass'])) {
                $row->visit_pass = $this->params['visit_pass'];
            }
            if (isset($this->params['is_record'])) {
                $row->is_record = $this->params['is_record'];
            }
            if (isset($this->params['allow_chat'])) {
                $row->allow_chat = $this->params['allow_chat'];
            }
            if (isset($this->params['users_limit'])) {
                $row->users_limit = $this->params['users_limit'];
            }
            if (isset($this->params['is_charge'])) {
                $row->is_charge = $this->params['is_charge'];
            }
            if (isset($this->params['cost'])) {
                $row->cost = $this->params['cost'];
            }
            if (isset($this->params['device_by'])) {
                $row->device_by = $this->params['device_by'];
            }
            if (isset($this->params['subject_id'])) {
                $row->subject_id = $this->params['subject_id'];
            }
            if (isset($this->params['class_room_id'])) {
                $row->class_room_id = $this->params['class_room_id'];
            }
            if (isset($this->params['status'])) {
                $row->status = $this->params['status'];
            }
            if (isset($this->params['tags'])) {
                $tags = $this->params['tags'];
                $tags = is_array($tags) ? implode(',', $tags) : strval($tags);
                $row->tags = $tags;
            }

            if (isset($this->params['file_ids'])) {
                $file_ids = $this->params['file_ids'];
                $row->file_ids = $file_ids;
            }
            $row->updated_time = date('Y-m-d H:i:s');
            if (!$row->update()) {
                $this->responseData = ['code' => \Code::ERROR_PARAMS, 'msg' => '直播修改失败', 'line' => __LINE__, 'data' => []];
                break;
            }
            $this->responseData['data']['id'] = $id;
        } while (false);
        $this->output();
    }

    /**
     * @brief 删除直播
     * @param [必填] id 直播Id
     */
    public function deleteAction()
    {
        do {
            if (!$this->checkUserLogin()) {
                break;
            }
            $uid = $this->uid;

            $id = intval($this->params['id']);
            $row = Live::findFirst(['id=' . $id, 'uid=' . $uid]);
            if (empty($row)) {
                $this->responseData = ['code' => \Code::ERROR, 'msg' => '直播不存在', 'line' => __LINE__, 'data' => []];
                break;
            }
            $this->db->begin();
            if ($row->delete() == false) {
                $this->db->rollback();
                $this->responseData = ['code' => \Code::ERROR_DB, 'msg' => '直播删除失败', 'line' => __LINE__, 'data' => []];
                break;
            }
            $this->db->commit();
            $this->responseData['data']['id'] = $id;
        } while (false);
        $this->output();
    }

    /**
     * @brief 进入直播统计
     * @param [必填] live_id 直播Id
     */
    public function inLiveAction()
    {
//        $this->params = array("live_id"=>42);
        do {
            $this->validation->add('live_id', new PresenceOf(['message' => '参数缺失:live_id']));
            $messages = $this->validation->validate($this->params);
            if (count($messages)) {
                foreach ($messages as $message) {
                    array_push($this->errors, strval($message));
                }
                $this->responseData = ['code' => \Code::ERROR_PARAMS, 'msg' => join(';', $this->errors), 'line' => __LINE__, 'data' => []];
                break;
            }
            if (!$this->checkUserLogin()) {
                break;
            }
            $uid = $this->uid;
            $live_id = intval($this->params['live_id']);
            $live = Live::findFirst(array("id=$live_id"));
            if (!$live) {
                $this->responseData = ['code' => \Code::ERROR, 'msg' => '直播不存在', 'line' => __LINE__, 'data' => []];
                break;
            }
            $liveUser = Liveuser::findFirst(array("uid=$uid and live_id=$live_id"));
            if(!$liveUser&&$live->uid!=$uid)
            {
                $liveUser = new Liveuser();
                $liveUser->uid = $uid;
                $liveUser->live_id = $live_id;
                if(!$liveUser->create())
                {
                    $this->responseData = ['code' => \Code::ERROR_DB, 'msg' => '直播计数失败', 'line' => __LINE__, 'data' => []];
                    break;
                }
            }
        } while (false);
        $this->output();
    }

    /**
     * @brief 获取个人参与的直播列表
     * @param page (选填) 当前页 默认是：1
     */
    public function getInLiveListAction()
    {
        do {
            if (!$this->checkUserLogin()) {
                break;
            }
            $uid = $this->uid;
            $page = isset($this->params['page'])&&(int)$this->params['page']>0?$this->params['page']:1;
            $limit = 12;
            $offset = ($page-1)*$limit;
            $live_arr_id = array();
            $rows = Liveuser::find(array("uid=$uid","order"=>"addtime desc","offset"=>$offset,"limit"=>$limit));
            foreach($rows as $row)
            {
                array_push($live_arr_id,$row->live_id);
            }
            $data = [];
            if(!empty($live_arr_id))
            {
                $rows = Live::find(array("id in(".join(',',$live_arr_id).")"));
                foreach ($rows as $row) {
                    if($row->start_time>date('Y-m-d H:i:s'))
                    {
                        $live_status = 0;
                    }
                    else if($row->start_time<date('Y-m-d H:i:s')&&$row->end_time>date('Y-m-d H:i:s'))
                    {
                        $live_status = 1;
                    }
                    else
                    {
                        $live_status = 2;
                    }
                    $data[] = [
                        'id' => $row->id,
                        'name' => $row->name,
                        'intro' => $row->intro,
                        'content' => $row->content,
                        'cover_pic' => $row->cover_pic,
                        'tags' => explode(',', $row->tags),
                        'start_time' => $row->start_time,
                        'end_time' => $row->end_time,
                        'publish_type' => $row->publish_type,
                        'group_id' => $row->group_id,
                        'visit_pass' => $row->visit_pass,
                        'is_record' => $row->is_record,
                        'allow_chat' => $row->allow_chat,
                        'users_limit' => $row->users_limit,
                        'is_charge' => $row->is_charge,
                        'cost' => $row->cost,
                        'device_by' => $row->device_by,
                        'live_status' => $live_status,
                        'created_time' => $row->created_time
                    ];
                }
            }
            $this->responseData['data']['rows'] = $data;
            $count = Liveuser::count(array("uid=$uid"));
            $this->responseData['data']['count'] = $count;
        } while (false);
        $this->output();
    }
}
