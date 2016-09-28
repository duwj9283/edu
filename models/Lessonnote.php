<?php

namespace Cloud\Models;
use Phalcon\Db\RawValue;
class Lessonnote extends \Phalcon\Mvc\Model
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
     * @var string
     */
    public $pic;

    /**
     *
     * @var string
     */
    public $content;


    /**
     *
     * @var string
     */
    public $time_point;

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
        $this->setSource("edu_lesson_note");
        $this->belongsTo('lesson_list_id', 'Cloud\Models\Lessonlist', 'id', array('alias'=>'Lessonlist'));
        $this->belongsTo('uid', 'Cloud\Models\Userinfo', 'uid', array('alias'=>'Userinfo'));
    }

    public function onConstruct()
    {
        $this->addtime = new RawValue('default');
        $this->pic = new RawValue('default');
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'edu_lesson_note';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Lessonnote[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Lessonnote
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
            'pic' => 'pic',
            'content' => 'content',
            'time_point' => 'time_point',
            'addtime' => 'addtime'
        );
    }

}
