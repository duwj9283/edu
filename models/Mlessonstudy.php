<?php

namespace Cloud\Models;

class Mlessonstudy extends \Phalcon\Mvc\Model
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
    public $m_lesson_id;

    /**
     *
     * @var integer
     */
    public $study_status;

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
        $this->setSource("edu_m_lesson_study");
        $this->belongsTo('m_lesson_id', 'Cloud\Models\Mlesson', 'id', array('alias'=>'Mlesson'));
        $this->belongsTo('uid', 'Cloud\Models\Userinfo', 'uid', array('alias'=>'Userinfo'));
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'edu_m_lesson_study';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Mlessonstudy[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Mlessonstudy
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
            'm_lesson_id' => 'm_lesson_id',
            'study_status' => 'study_status',
            'addtime' => 'addtime'
        );
    }

}
