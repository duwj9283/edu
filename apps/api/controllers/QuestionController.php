<?php
namespace Cloud\API\Controllers;
use Cloud\Models\Question;
use Cloud\Models\Questionitem;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\StringLength;
use Phalcon\Validation\Validator\InclusionIn;
use Phalcon\Mvc\Model\Resultset\Simple as Resultset;
/**
 * @brief 习题管理
 */
class QuestionController extends ControllerBase
{
    /**
     * @brief 添加/修改习题
     * @param id  （添加时为空）
     * @param type
     * @param title
     * @param content
     * @param difficulty
     * @param analysis
     * @param knowledge_point
     * @param answer
     * @param items 选项（数组）
     */
    public function addQuestionAction()
    {
//        $this->params = array('id'=>1,'type'=>1,'title'=>'你是A型血吗？','content'=>'测试content',
//            'difficulty'=>1,'analysis'=>'测试analysis','knowledge_point'=>'测试knowledge_point','answer'=>'对',
//            'items'=>array(
//            array('id'=>1,'title'=>'对','type'=>'radio'),
//            array('id'=>2,'title'=>'错','type'=>'radio')
//        ));
        do {
            $this->validation->add('id', new PresenceOf(array('message'=>'参数缺失:id')));
            $this->validation->add('type', new PresenceOf(array('message'=>'参数缺失:type')));
            $this->validation->add('title', new PresenceOf(array('message'=>'参数缺失:title')));
            $this->validation->add('content', new PresenceOf(array('message'=>'参数缺失:content')));
            $this->validation->add('difficulty', new PresenceOf(array('message'=>'参数缺失:difficulty')));
            $this->validation->add('analysis', new PresenceOf(array('message'=>'参数缺失:analysis')));
            $this->validation->add('knowledge_point', new PresenceOf(array('message'=>'参数缺失:knowledge_point')));
            $this->validation->add('answer', new PresenceOf(array('message'=>'参数缺失:answer')));
            $this->validation->add('type', new InclusionIn(array('message'=>"参数type值只能为:1/2/3",
                'domain'=>array('1', '2', '3'),
                'allowEmpty'=>false)));
            $this->validation->add('difficulty', new InclusionIn(array('message'=>"参数difficulty值只能为:1/2/3",
                'domain'=>array('1', '2', '3'),
                'allowEmpty'=>false)));
            $this->validation->add('items', new PresenceOf(array('message'=>'参数缺失:items')));
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
            $id = (int)$this->params['id'];
            $uid = $this->uid;
            $title = $this->params['title'];
            $type = (int)$this->params['type'];
            $content = $this->params['content'];
            $difficulty = (int)$this->params['difficulty'];
            $analysis = $this->params['analysis'];
            $knowledge_point = $this->params['knowledge_point'];
            $answer = $this->params['answer'];
            $items = $this->params['items'];
            if(!is_array($items))
            {
                $this->responseData = array("code"=>\Code::ERROR_PARAMS,"msg"=>'items必须为数据格式',"line"=>__LINE__,"data"=>array());
                break;
            }
            $this->db->begin();
            if($id>0)
            {
                $question = Question::findFirst(array("id=$id"));
                if(!$question)
                {
                    $this->responseData = array("code"=>\Code::ERROR_PARAMS,"msg"=>'习题不存在',"line"=>__LINE__,"data"=>array());
                    break;
                }
            }
            else
            {
                $question = new Question();
                $question->addtime = $question->setAddtime();
            }
            $question->title = $title;
            $question->uid = $uid;
            $question->type = $type;
            $question->content = $content;
            $question->difficulty = $difficulty;
            $question->analysis = $analysis;
            $question->knowledge_point = $knowledge_point;
            $question->answer = $answer;
            if(!$question->save())
            {
                $this->db->rollback();
                $this->responseData = array("code"=>\Code::ERROR_PARAMS,"msg"=>'习题添加失败',"line"=>__LINE__,"data"=>array());
                break;
            }
            $addQuestionItemSql = "INSERT INTO edu_question_item(id,title,question_id,type) VALUES";
            $question_item_ids = array();
            foreach($items as $item)
            {
                array_push($question_item_ids,$item['id']);
                $addQuestionItemSql .="(".$item['id'].",'".$item['title']."',".$question->id.",'".$item['type']."'),";
            }
            $duplicate = " ON DUPLICATE KEY UPDATE title=VALUES(title),question_id=VALUES(question_id),type=VALUES(type)";

            //删除多余的选项
            $question_item_dbs = Questionitem::find(array("question_id=".$question->id));
            $question_item_db_ids = array();
            foreach($question_item_dbs as $question_item_db)
            {
                array_push($question_item_db_ids,$question_item_db->id);
            }
            $diff = array_diff($question_item_db_ids,$question_item_ids);
            if(!empty($diff))
            {
                $delSql = "delete from edu_question_item where id in(".join(',',$diff).")";
                $del = $this->db->query($delSql);
                if(!$del)
                {
                    $this->db->rollback();
                    $this->responseData = array("code"=>\Code::ERROR_DB,"msg"=>'添加失败2',"line"=>__LINE__,"data"=>array());
                    break;
                }
            }
            $addQuestionItemSql = substr($addQuestionItemSql,0,strlen($addQuestionItemSql)-1);
            $add = $this->db->query($addQuestionItemSql.$duplicate);
            if(!$add)
            {
                $this->db->rollback();
                $this->responseData = array("code"=>\Code::ERROR_DB,"msg"=>'添加失败',"line"=>__LINE__,"data"=>array());
                break;
            }
            $this->db->commit();
            $question_id = $question->id;
            $question = Question::findFirst(array("uid=$uid and id=$question_id"));
            $questionItem = Questionitem::find(array("question_id=$question_id"));
            $this->responseData['data']['question'] = $question->toArray();
            $this->responseData['data']['question_item'] = $questionItem->toArray();
        }while(false);
        $this->output();
    }

    /**
     * @brief 获取习题
     * @param question_id
     */
    public function getQuestionByIdAction()
    {
        do {
            $this->validation->add('question_id', new PresenceOf(array('message'=>'参数缺失:question_id')));
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
            $question_id = (int)$this->params['question_id'];;
            $uid = $this->uid;
            $question = Question::findFirst(array("uid=$uid and id=$question_id"));
            if(!$question)
            {
                $this->responseData = array("code"=>\Code::ERROR_DB,"msg"=>'习题不存在',"line"=>__LINE__,"data"=>array());
                break;
            }
            $questionItem = Questionitem::find(array("question_id=$question_id"));
            $this->responseData['data']['question'] = $question->toArray();
            $this->responseData['data']['question_item'] = $questionItem->toArray();
        }while(false);
        $this->output();
    }

    /**
     * @brief 获取习题库列表
     * @param keywords (可选)
     */
    public function getQuestionListAction()
    {
        do {
            $where = '';
            $keywords = isset($this->params['$keywords'])?$this->params['$keywords']:null;
            if(!empty($keywords))
            {
                $where = " and title like '%$keywords%'";
            }
            $uid = $this->uid;
            $questions = Question::find(array("uid=$uid $where","order"=>"sort asc,id desc"));

            $question_list = array();
            $question_ids = array();
            foreach($questions as $question)
            {
                $question_list[$question->id] = $question->toArray();
                array_push($question_ids,$question->id);
            }
            if(!empty($question_ids))
            {
                $questionItems = Questionitem::find(array("question_id in (".join(',',$question_ids).")"));
                foreach($questionItems as $questionItem)
                {
                    $question_list[$questionItem->question_id]['item'][] = $questionItem->toArray();
                }
            }
            $this->responseData['data']['question_list'] = $question_list;

        }while(false);
        $this->output();
    }

    /**
     * @brief 删除习题
     * @param question_id 问题ID
     */
    public function delQuestionAction()
    {
        do {
            $this->validation->add('question_id', new PresenceOf(array('message'=>'参数缺失:question_id')));
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
            $question_id = (int)$this->params['question_id'];;
            $uid = $this->uid;
            $question = Question::findFirst(array("uid=$uid and id=$question_id"));
            if(!$question)
            {
                $this->responseData = array("code"=>\Code::ERROR_DB,"msg"=>'习题不存在',"line"=>__LINE__,"data"=>array());
                break;
            }
            $this->db->begin();
            if( !$question->delete()){
                $this->db->rollback();
                $this->responseData = array("code"=>\Code::ERROR_DB,"msg"=>'习题删除失败',"line"=>__LINE__,"data"=>array());
                break;
            }
            //  删除选项
            $sql = "DELETE FROM edu_question_item where question_id=$question_id";
            $result= $this->db->query($sql);
            if(!$result){
                $this->db->rollback();
                $this->responseData = array("code"=>\Code::ERROR_DB,"msg"=>'习题选项删除失败',"line"=>__LINE__,"data"=>array());
                break;
            }
            $this->db->commit();
        }while(false);
        $this->output();
    }

}