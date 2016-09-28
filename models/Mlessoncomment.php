<?php

namespace Cloud\Models;

class Mlessoncomment extends \Phalcon\Mvc\Model
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
    public $m_lesson_id;

    /**
     *
     * @var integer
     */
    public $uid;

    /**
     *
     * @var string
     */
    public $content;

    /**
     *
     * @var integer
     */
    public $ref_id;

    /**
     *
     * @var integer
     */
    public $ref_uid;

    /**
     *
     * @var string
     */
    public $create_time;

    /**
     *
     * @var integer
     */
    public $del;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSource("edu_m_lesson_comment");
        $this->belongsTo('uid', 'Cloud\Models\Userinfo', 'uid', array('alias'=>'UserInfo'));
        $this->belongsTo('ref_uid', 'Cloud\Models\Userinfo', 'uid', array('alias'=>'RefUserInfo'));
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'edu_m_lesson_comment';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Mlessoncomment[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Mlessoncomment
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
            'm_lesson_id' => 'm_lesson_id',
            'uid' => 'uid',
            'content' => 'content',
            'ref_id' => 'ref_id',
            'ref_uid' => 'ref_uid',
            'create_time' => 'create_time',
            'del' => 'del'
        );
    }

}
