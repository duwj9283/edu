<?php

namespace Cloud\Models;
use Phalcon\Db\RawValue;
class Live extends \Phalcon\Mvc\Model
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
    public $name;

    /**
     *
     * @var string
     */
    public $intro;

    /**
     *
     * @var string
     */
    public $content;

    /**
     *
     * @var string
     */
    public $cover_pic;

    /**
     *
     * @var string
     */
    public $tags;

    /**
     *
     * @var string
     */
    public $start_time;

    /**
     *
     * @var string
     */
    public $end_time;

    /**
     *
     * @var integer
     */
    public $publish_type;

    /**
     *
     * @var integer
     */
    public $group_id;

    /**
     *
     * @var string
     */
    public $visit_pass;

    /**
     *
     * @var integer
     */
    public $is_record;

    /**
     *
     * @var integer
     */
    public $allow_chat;

    /**
     *
     * @var integer
     */
    public $users_limit;

    /**
     *
     * @var integer
     */
    public $is_charge;

    /**
     *
     * @var integer
     */
    public $cost;

    /**
     *
     * @var integer
     */
    public $device_by;

    /**
     *
     * @var integer
     */
    public $status;

    /**
     *
     * @var string
     */
    public $created_time;

    /**
     *
     * @var string
     */
    public $updated_time;

    /**
     *
     * @var integer
     */
    public $subject_id;

    /**
     *
     * @var integer
     */
    public $class_room_id;

    /**
     *
     * @var string
     */
    public $file_ids;

    /**
     *
     * @var string
     */
    public $video_path;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSource("edu_live");
        $this->belongsTo('uid', 'Cloud\Models\Userinfo', 'uid', array('alias'=>'UserInfo'));
    }

    public function onConstruct()
    {
        $this->file_ids = new RawValue('default');
        $this->video_path = new RawValue('default');
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'edu_live';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Live[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Live
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
            'name' => 'name',
            'intro' => 'intro',
            'content' => 'content',
            'cover_pic' => 'cover_pic',
            'tags' => 'tags',
            'start_time' => 'start_time',
            'end_time' => 'end_time',
            'publish_type' => 'publish_type',
            'group_id' => 'group_id',
            'visit_pass' => 'visit_pass',
            'is_record' => 'is_record',
            'cost' => 'cost',
            'allow_chat' => 'allow_chat',
            'users_limit' => 'users_limit',
            'is_charge' => 'is_charge',
            'device_by' => 'device_by',
            'status' => 'status',
            'created_time' => 'created_time',
            'updated_time' => 'updated_time',
            'subject_id' => 'subject_id',
            'class_room_id' => 'class_room_id',
            'file_ids' => 'file_ids',
            'video_path' => 'video_path'
        );
    }

}
