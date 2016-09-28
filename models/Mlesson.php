<?php

namespace Cloud\Models;
use Phalcon\Db\RawValue;
class Mlesson extends \Phalcon\Mvc\Model
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
    public $file_ids;

    /**
     *
     * @var integer
     */
    public $file;

    /**
     *
     * @var string
     */
    public $group_ids;

    /**
     *
     * @var string
     */
    public $password;

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
     * @var integer
     */
    public $subject_id;

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
        $this->setSource("edu_m_lesson");
        $this->belongsTo('uid', 'Cloud\Models\Userinfo', 'uid', array('alias'=>'Userinfo'));
    }

    public function onConstruct()
    {
        $this->desc = new RawValue('default');
        $this->label = new RawValue('default');
        $this->file_ids = new RawValue('default');
        $this->type = new RawValue('default');
        $this->password = new RawValue('default');
        $this->status = new RawValue('default');
        $this->group_ids = new RawValue('default');
        $this->subject_id = new RawValue('default');
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'edu_m_lesson';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Mlesson[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Mlesson
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
            'pic' => 'pic',
            'label' => 'label',
            'desc' => 'desc',
            'file' => 'file',
            'file_ids' => 'file_ids',
            'group_ids' => 'group_ids',
            'password' => 'password',
            'type' => 'type',
            'status' => 'status',
            'subject_id' => 'subject_id',
            'addtime' => 'addtime'
        );
    }

}
