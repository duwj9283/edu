<?php

namespace Cloud\Models;
use Phalcon\Db\RawValue;
class Question extends \Phalcon\Mvc\Model
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
     * @var string
     */
    public $title;

    /**
     *
     * @var string
     */
    public $content;

    /**
     *
     * @var integer
     */
    public $difficulty;

    /**
     *
     * @var integer
     */
    public $type;

    /**
     *
     * @var integer
     */
    public $status;

    /**
     *
     * @var string
     */
    public $analysis;

    /**
     *
     * @var string
     */
    public $knowledge_point;

    /**
     *
     * @var string
     */
    public $answer;

    /**
     *
     * @var integer
     */
    public $sort;

    /**
     *
     * @var string
     */
    public $addtime;

    public function setAddtime()
    {
        $this->addtime = date('Y-m-d H:i:s');
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSource('edu_question');
    }

    public function onConstruct()
    {
        $this->sort = new RawValue('default');
        $this->status = new RawValue('default');
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'edu_question';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Question[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Question
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
            'title' => 'title',
            'content' => 'content',
            'difficulty' => 'difficulty',
            'type' => 'type',
            'status' => 'status',
            'analysis' => 'analysis',
            'knowledge_point' => 'knowledge_point',
            'answer' => 'answer',
            'sort' => 'sort',
            'addtime' => 'addtime'
        );
    }

}
