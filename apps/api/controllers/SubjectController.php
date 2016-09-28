<?php
namespace Cloud\API\Controllers;
use Cloud\Models\Mlesson;
use Cloud\Models\Subject;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\StringLength;
use Phalcon\Validation\Validator\InclusionIn;
use Phalcon\Mvc\Model\Resultset\Simple as Resultset;

/**
 * @brief 翻页获取专业
 * @param (选填)keywords 关键词
 */
class SubjectController extends ControllerBase
{
    public function getChildSubjectAction()
    {
        do {
            $page = isset($this->params['page']) && (int)$this->params['page'] > 0 ? $this->params['page'] : 1;
            $keywords = isset($this->params['keywords'])?$this->params['keywords'] : '';
            $limit = 30;
            $offset = ($page - 1) * $limit;
            $condition = '';
            if (!empty($keywords)) {
                $condition .= " and subject_name like '%$keywords%'";
            }
            $subjects = Subject::find(array("father_id>0 and visible=1 $condition", "order" => "id asc", "offset" => $offset, "limit" => $limit));
            $subjectArr = array();
            foreach($subjects as $k=>$subject)
            {
                $subjectArr[$k] = $subject->toArray();
                $mlessonCount = Mlesson::count(array("subject_id=".$subject->id));
                $subjectArr[$k]['mlessonCount'] = $mlessonCount;
            }
            $this->responseData['data']['subjects'] = $subjectArr;
        } while (false);
        $this->output();
    }
}