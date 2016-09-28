<?php

namespace Cloud\Models;
use Phalcon\Db\RawValue;
class Userinfo extends \Phalcon\Mvc\Model
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
    public $role_id;

    /**
     *
     * @var string
     */
    public $realname;
    /**
     *
     * @var string
     */
    public $nick_name;
    /**
     *
     * @var string
     */
    public $desc;
    /**
     *
     * @var string
     */
    public $qq;

    /**
     *
     * @var string
     */
    public $sex;

    /**
     *
     * @var string
     */
    public $headpic;

    /**
     *
     * @var string
     */
    public $email;

    /**
     *
     * @var string
     */
    public $city;

    /**
     *
     * @var string
     */
    public $job;

    /**
     *
     * @var integer
     */
    public $father_subject;

    /**
     *
     * @var integer
     */
    public $subject;


    /**
     *
     * @var string
     */
    public $class;


    /**
     *
     * @var string
     */
    public $class_year;

    /**
     *
     * @var integer
     */
    public $follow_count;

    /**
     *
     * @var integer
     */
    public $push_file_count;

    /**
     *
     * @var integer
     */
    public $visited_count;

    /**
     *
     * @var string
     */
    public $admin_subject;


    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSource('edu_user_info');
        $this->useDynamicUpdate(true);
        $this->setup(array("notNullValidations"=>false));

    }

    public function onConstruct()
    {
        $this->email = new RawValue('default');
        $this->realname = new RawValue('default');
        $this->nick_name = new RawValue('default');
        $this->desc = new RawValue('default');
        $this->qq = new RawValue('default');
        $this->headpic = new RawValue('default');
        $this->city = new RawValue('default');
        $this->job = new RawValue('default');
        $this->father_subject = new RawValue('default');
        $this->subject = new RawValue('default');
        $this->class = new RawValue('default');
        $this->class_year = new RawValue('default');
        $this->follow_count = new RawValue('default');
        $this->push_file_count = new RawValue('default');
        $this->visited_count = new RawValue('default');
        $this->admin_subject = new RawValue('default');
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
            'role_id' => 'role_id',
            'email' => 'email',
            'realname' => 'realname',
            'nick_name' => 'nick_name',
            'desc' => 'desc',
            'qq' => 'qq',
            'sex' => 'sex',
            'headpic' => 'headpic',
            'city' => 'city',
            'job' => 'job',
            'father_subject' => 'father_subject',
            'subject' => 'subject',
            'class' => 'class',
            'class_year' => 'class_year',
            'follow_count' => 'follow_count',
            'push_file_count' => 'push_file_count',
            'visited_count'=>'visited_count',
            'admin_subject'=>'admin_subject'
        );
    }
}
