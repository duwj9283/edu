<?php

namespace Cloud\Models;

class Activity extends \Phalcon\Mvc\Model
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
    public $address;

    /**
     *
     * @var string
     */
    public $cover_pic;

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
     * @var string
     */
    public $content;

    /**
     *
     * @var integer
     */
    public $type;

    /**
     *
     * @var integer
     */
    public $verify;

    /**
     *
     * @var integer
     */
    public $users_limit;

    /**
     *
     * @var integer
     */
    public $is_pay;

    /**
     *
     * @var double
     */
    public $price;

    /**
     *
     * @var integer
     */
    public $recommend;

    /**
     *
     * @var string
     */
    public $description;

    /**
     *
     * @var string
     */
    public $password;

    /**
     *
     * @var string
     */
    public $group_ids;

    /**
     *
     * @var integer
     */
    public $status;

    /**
     *
     * @var string
     */
    public $create_time;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSource("edu_activity");
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'edu_activity';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Activity[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Activity
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
            'address' => 'address',
            'cover_pic' => 'cover_pic',
            'start_time' => 'start_time',
            'end_time' => 'end_time',
            'content' => 'content',
            'type' => 'type',
            'verify' => 'verify',
            'users_limit' => 'users_limit',
            'is_pay' => 'is_pay',
            'price' => 'price',
            'recommend' => 'recommend',
            'description' => 'description',
            'password' => 'password',
            'group_ids' => 'group_ids',
            'status' => 'status',
            'create_time' => 'create_time'
        );
    }

}
