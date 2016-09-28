<?php
namespace Cloud\Live\Controllers;
use Cloud\Models\Subject;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\StringLength;
use Phalcon\Validation\Validator\InclusionIn;
use Phalcon\Mvc\Model\Resultset\Simple as Resultset;

/**
 * @brief 获取资源接口
 */
class SourceController extends ControllerBase
{
    public function getSubjectAction(){
        do{
            $father_id = $this->request->getPost('father_id');
            $father_id = isset($father_id)?(int)$father_id:0;
            $keywords = $this->request->getPost('keywords');
            $keywords = isset($keywords)?trim($keywords):'';
            $condition = '';
            if($father_id>0)
            {
                $condition .= "father_id=$father_id";
            }
            else
            {
                if($keywords)
                {
                    $condition .= "subject_name like '%$keywords%' and father_id=0";
                }
            }
            $subjectObject = Subject::find(array($condition));
            $subject = $subjectObject->toArray();
            $subjectArray = array();
            $subjectChild = array();
            foreach($subject as $key=>$value){
                if($value['father_id'] == 0){
                    $subjectArray[$value['id']] = $value;
                }else{
                    $subjectChild[$value['father_id']][] =$value;
                }
            }
            foreach($subjectChild as $key=>$value){
                $subjectArray[$key]['child'] = $value;
            }
            $this->set_data($subjectArray);

        }while(false);
        $this->output();
    }
}
