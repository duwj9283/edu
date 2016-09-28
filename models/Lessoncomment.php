<?php

namespace Cloud\Models;
use Phalcon\Db\RawValue;
class Lessoncomment extends \Phalcon\Mvc\Model
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
    public $lesson_id;

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

    public function setCreate_time()
    {
        $this->create_time = date('Y-m-d H:i:s');
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSource('edu_lesson_comment');
        $this->belongsTo('uid', 'Cloud\Models\Userinfo', 'uid', array('alias'=>'UserInfo'));
        $this->belongsTo('ref_uid', 'Cloud\Models\Userinfo', 'uid', array('alias'=>'RefUserInfo'));
    }

    public function onConstruct()
    {
        $this->del = new RawValue('default');
        $this->ref_uid = new RawValue('default');
        $this->ref_id = new RawValue('default');
    }
    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'edu_lesson_comment';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Lessoncomment[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Lessoncomment
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
            'lesson_id' => 'lesson_id',
            'uid' => 'uid',
            'content' => 'content',
            'ref_id' => 'ref_id',
            'ref_uid' => 'ref_uid',
            'create_time' => 'create_time',
            'del' => 'del'
        );
    }

}
