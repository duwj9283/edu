<?php

namespace Cloud\Models;
use Phalcon\Db\RawValue;
class Lesson extends \Phalcon\Mvc\Model
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
    public $subtitle;

    /**
     *
     * @var string
     */
    public $pic;

    /**
     *
     * @var string
     */
    public $label;

    /**
     *
     * @var string
     */
    public $desc;

    /**
     *
     * @var string
     */
    public $description;

    /**
     *
     * @var integer
     */
    public $price;

    /**
     *
     * @var integer
     */
    public $status;

    /**
     *
     * @var integer
     */
    public $level_id;

    /**
     *
     * @var integer
     */
    public $type;

    /**
     *
     * @var integer
     */
    public $subject_id;

    /**
     *
     * @var integer
     */
    public $father_subject_id;

    /**
     *
     * @var string
     */
    public $push_time;

    /**
     *
     * @var integer
     */
    public $study_count;
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
        $this->setSource('edu_lesson');
        $this->belongsTo('uid', 'Cloud\Models\Userinfo', 'uid', array('alias'=>'UserInfo'));
    }

    public function onConstruct()
    {
        $this->addtime = new RawValue('default');
        $this->push_time = new RawValue('default');
        $this->desc = new RawValue('default');
        $this->label = new RawValue('default');
        $this->price = new RawValue('default');
        $this->subtitle = new RawValue('default');
        $this->father_subject_id = new RawValue('default');
        $this->subject_id = new RawValue('default');
        $this->description = new RawValue('default');
        $this->study_count = new RawValue('default');
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'edu_lesson';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Lesson[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Lesson
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
            'subtitle' => 'subtitle',
            'pic' => 'pic',
            'label' => 'label',
            'desc' => 'desc',
            'description' => 'description',
            'price' => 'price',
            'status' => 'status',
            'father_subject_id' => 'father_subject_id',
            'subject_id' => 'subject_id',
            'level_id' => 'level_id',
            'type' => 'type',
            'push_time' => 'push_time',
            'study_count' => 'study_count',
            'addtime' => 'addtime'
        );
    }
    public function getFrontTmp(){
        return "/frontend/course/getFrontImageThumb/".$this->id."/550/260";
    }

}
