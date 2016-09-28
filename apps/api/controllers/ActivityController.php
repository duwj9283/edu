<?php
namespace Cloud\API\Controllers;

use Cloud\Models\Activity;
use Cloud\Models\Activityquestion;
use Phalcon\Validation\Validator\InclusionIn;
use Phalcon\Validation\Validator\PresenceOf;

/**
 * @brief 活动数据接口
 */
class ActivityController extends ControllerBase
{
    /**
     * @brief 获取所有公开的列表
     * @param page (选填) 当前页 默认是：1
     */
    public function getPubListAction()
    {
        do {
            $page = (int)$this->params['page'] > 0 ? $this->params['page'] : 1;
            $limit = 12;  //默认单次返回的数据数量
            $offset = ($page - 1) * $limit;
            $rows = Activity::find(["type=1", "order"=>"start_time asc", 'limit'=>$limit, 'offset'=>$offset]);
            $data = [];
            foreach ($rows as $row) {
                $data[] = [
                    'id' => $row->id,
                    'title' => $row->title,
                    'cover_pic' => $row->cover_pic,
                    'start_time' => $row->start_time,
                    'end_time' => $row->end_time,
                    'address' => $row->address,
                ];
            }
            $this->responseData['data']['rows'] = $data;
        } while (false);
        $this->output();
    }

    /**
     * @brief 获取个人创建的活动列表
     */
    public function getMyListAction()
    {
        do {
            if (!$this->checkUserLogin()) {
                break;
            }
            $uid = $this->uid;
            $rows = Activity::find(['uid=' . $uid]);
            $data = [];
            foreach ($rows as $row) {
                $data[] = [
                    'id' => $row->id,
                    'title' => $row->title,
                    'cover_pic' => $row->cover_pic,
                    'start_time' => $row->start_time,
                    'end_time' => $row->end_time,
                    'address' => $row->address,
                ];
            }
            $this->responseData['data']['rows'] = $data;
        } while (false);
        $this->output();
    }

    /**
     * @brief 获取活动信息
     * @param [必填] id 活动ID
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
            $row = Activity::findFirst(['uid=' . $uid, 'id=' . $id]);
            if (!$row) {
                $this->responseData = ['code' => \Code::ERROR_PARAMS, 'msg' => '课程不存在', 'line' => __LINE__, 'data' => []];
                break;
            }
            $data = [
                'id' => $row->id,
                'title' => $row->title,
                'address' => $row->address,
                'cover_pic' => $row->cover_pic,
                'start_time' => $row->start_time,
                'end_time' => $row->end_time,
                'content' => $row->content,
                'type' => $row->type,
                'verify' => $row->verify,
                'users_limit' => $row->users_limit,
                'is_pay' => $row->is_pay,
                'price' => $row->price,
                'description' => $row->description,
                'password' => $row->password,
                'group_ids' => explode(',', $row->group_ids),
            ];
            $rows = Activityquestion::find(['activity_id = ' . $id]);
            foreach ($rows as $row) {
                $data['questions'][] = $row->question;
            }
            $this->responseData['data'] = $data;
        } while (false);
        $this->output();
    }

    /**
     * @brief 添加活动
     * @param [必填] title 活动标题
     * @param [必填] address 活动地址
     * @param [必填] start_time 开始时间
     * @param [必填] end_time 结束时间
     * @param [必填] type 活动类型: 1：公开 2：群组 3：私有（公开含密码）
     * @param content 活动内容
     * @param cover_pic 活动封面
     * @param verify 活动报名是否需要审核,0不需要审核,1需要审核
     * @param is_pay 该活动是否付费，0、免费1、付费（默认0）
     * @param price 付费活动价格
     * @param description 活动描述
     * @param password 活动类型为公共时设置的密码
     * @param group_ids 群组id,多个群组ID之间用英文逗号间隔
     * @param questions 活动问题（多个问题请使用数组形式）
     * @param users_limit 人数限制 0:不限制
     */
    public function createAction()
    {
        do {
            $this->validation->add('title', new PresenceOf(['message' => '参数缺失:title']));
            $this->validation->add('address', new PresenceOf(['message' => '参数缺失:address']));
            $this->validation->add('start_time', new PresenceOf(['message' => '参数缺失:start_time']));
            $this->validation->add('end_time', new PresenceOf(['message' => '参数缺失:end_time']));
            $this->validation->add('type', new InclusionIn([
                'message' => '参数type值只能为:1/2/3',
                'domain' => ['1', '2', '3'],
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

            $title = strval($this->params['title']);
            $address = strval($this->params['address']);
            $cover_pic = strval($this->params['cover_pic']);
            $start_time = strval($this->params['start_time']);
            $end_time = strval($this->params['end_time']);
            $content = strval($this->params['content']);
            $description = strval($this->params['description']);
            $password = strval($this->params['password']);
            $type = intval($this->params['type']);
            $verify = intval($this->params['verify']);
            $users_limit = intval($this->params['users_limit']);
            $is_pay = intval($this->params['is_pay']);
            $price = floatval($this->params['price']);
            $group_ids = isset($this->params['group_ids']) ?: '';

            $group_ids = is_array($group_ids) ? implode(',', $group_ids) : strval($group_ids);
            $recommend = 0;
            $status = 1;
            $create_time = date('Y-m-d H:i:s');

            $data = compact('uid', 'title', 'address', 'cover_pic', 'start_time', 'end_time', 'content', 'type', 'verify', 'users_limit', 'is_pay', 'price', 'recommend', 'description', 'password', 'group_ids', 'status', 'create_time');
            $arow = new Activity;
            foreach ($data as $k => $v) {
                $arow->$k = $v;
            }
            if ($arow->create()) {
                $activity_id = $arow->id;
                $questions = isset($this->params['questions']) ? $this->params['questions'] : '';
                if (is_array($questions) && count($questions)) {
                    $query = 'INSERT INTO edu_activity_question(id,activity_id,question) VALUES ';
                    foreach ($questions as $val) {
                        $query .= '(0, ' . $activity_id . ', "' . $val . '"),';
                    }
                    $duplicate = ' ON DUPLICATE KEY UPDATE activity_id=VALUES(activity_id), question=VALUES(question)';
                    $result = $this->db->query(substr($query, 0, -1) . $duplicate);
                    if ($result == false) {
                        $this->responseData = ['code' => \Code::ERROR_PARAMS, 'msg' => '课程添加失败', 'line' => __LINE__, 'data' => []];
                        break;
                    }
                } else {
                    if (!empty($questions)) {
                        $nrow = new Activityquestion;
                        $nrow->activity_id = $activity_id;
                        $nrow->question = strval($questions);
                        $nrow->create();
                    }
                }
                $this->responseData['data']['id'] = $activity_id;
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
     * @brief 修改活动
     * @param [必填] id 活动Id
     */
    public function updateAction()
    {
        do {
            if (!$this->checkUserLogin()) {
                break;
            }
            $uid = $this->uid;

            $id = intval($this->params['id']);
            $row = Activity::findFirst(['id=' . $id, 'uid=' . $uid]);
            if (empty($row)) {
                $this->responseData = ['code' => \Code::ERROR, 'msg' => '活动不存在', 'line' => __LINE__, 'data' => []];
                break;
            }
            if (isset($this->params['title'])) {
                $row->title = $this->params['title'];
            }
            if (isset($this->params['address'])) {
                $row->address = $this->params['address'];
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
            if (isset($this->params['content'])) {
                $row->content = $this->params['content'];
            }
            if (isset($this->params['type'])) {
                $row->type = $this->params['type'];
            }
            if (isset($this->params['verify'])) {
                $row->verify = $this->params['verify'];
            }
            if (isset($this->params['users_limit'])) {
                $row->users_limit = $this->params['users_limit'];
            }
            if (isset($this->params['is_pay'])) {
                $row->is_pay = $this->params['is_pay'];
            }
            if (isset($this->params['price'])) {
                $row->price = $this->params['price'];
            }
            if (isset($this->params['description'])) {
                $row->description = $this->params['description'];
            }
            if (isset($this->params['password'])) {
                $row->password = $this->params['password'];
            }
            if (isset($this->params['group_ids'])) {
                $group_ids = $this->params['group_ids'];
                $group_ids = is_array($group_ids) ? implode(',', $group_ids) : strval($group_ids);
                $row->group_ids = $group_ids;
            }
            if (!$row->update()) {
                $this->responseData = ['code' => \Code::ERROR_PARAMS, 'msg' => '活动修改失败', 'line' => __LINE__, 'data' => []];
                break;
            }
            if (isset($this->params['questions'])) {
                $questions = isset($this->params['questions']) ? $this->params['questions'] : '';
                $query = 'delete from edu_activity_question where activity_id=' . $id;
                if ($this->db->query($query)) {
                    if (is_array($questions) && count($questions)) {
                        $query = 'INSERT INTO edu_activity_question(id, activity_id, question) VALUES ';
                        foreach ($questions as $val) {
                            $query .= '(0, ' . $id . ', "' . $val . '"),';
                        }
                        $duplicate = ' ON DUPLICATE KEY UPDATE activity_id=VALUES(activity_id), question=VALUES(question)';
                        $result = $this->db->query(substr($query, 0, -1) . $duplicate);
                        if ($result == false) {
                            $this->responseData = ['code' => \Code::ERROR_PARAMS, 'msg' => '课程添加失败', 'line' => __LINE__, 'data' => []];
                            break;
                        }
                    } else {
                        if (!empty($questions)) {
                            $nrow = new Activityquestion;
                            $nrow->activity_id = $id;
                            $nrow->question = strval($questions);
                            $nrow->create();
                        }
                    }
                }
            }
            $this->responseData['data']['id'] = $id;
        } while (false);
        $this->output();
    }

    /**
     * @brief 删除活动
     * @param [必填] id 活动Id
     */
    public function deleteAction()
    {
        do {
            if (!$this->checkUserLogin()) {
                break;
            }
            $uid = $this->uid;

            $id = intval($this->params['id']);
            $row = Activity::findFirst(['id=' . $id, 'uid=' . $uid]);
            if (empty($row)) {
                $this->responseData = ['code' => \Code::ERROR, 'msg' => '活动不存在', 'line' => __LINE__, 'data' => []];
                break;
            }
            $this->db->begin();
            if ($row->delete() == false) {
                $this->db->rollback();
                $this->responseData = ['code' => \Code::ERROR_DB, 'msg' => '活动删除失败', 'line' => __LINE__, 'data' => []];
                break;
            }
            $query = 'delete from edu_activity_question where activity_id=' . $id;
            if ($this->db->query($query) == false) {
                $this->db->rollback();
                $this->responseData = ['code' => \Code::ERROR_DB, 'msg' => '活动问题删除失败', 'line' => __LINE__, 'data' => []];
                break;
            }
            $this->db->commit();
            $this->responseData['data']['id'] = $id;
        } while (false);
        $this->output();
    }
}
