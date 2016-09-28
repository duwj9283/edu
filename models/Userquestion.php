<?php

namespace Cloud\Models;
use Phalcon\Db\RawValue;
class Userquestion extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var integer
     */
    public $uid;

    /**
     *
     * @var integer
     */
    public $lesson_id;

    /**
     *
     * @var integer
     */
    public $lesson_list_id;

    /**
     *
     * @var integer
     */
    public $question_id;

    /**
     *
     * @var string
     */
    public $true_answer;

    /**
     *
     * @var string
     */
    public $answer;

    /**
     *
     * @var string
     */
    public $addtime;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSource("edu_user_question");
    }

    public function onConstruct()
    {
        $this->addtime = new RawValue('default');
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'edu_user_question';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Userquestion[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Userquestion
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

    /**
     * Independent Column Mapping.
     * Keys are the real names in the table and the values their names in the application
     *
     * @return array
     */
    public function columnMap()
    {
        return array(
            'id' => 'id',
            'uid' => 'uid',
            'lesson_id' => 'lesson_id',
            'lesson_list_id' => 'lesson_list_id',
            'question_id' => 'question_id',
            'true_answer' => 'true_answer',
            'answer' => 'answer',
            'addtime' => 'addtime'
        );
    }

}
