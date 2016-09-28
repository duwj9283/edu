<?php

namespace Cloud\Models;
use Phalcon\Db\RawValue;
class Usercapacity extends \Phalcon\Mvc\Model
{
    /**
     *
     * @var integer
     */
    public $uid;

    /**
     *
     * @var integer
     */
    public $capacity_all;

    /**
     *
     * @var integer
     */
    public $capacity_used;

    /**
     *
     * @var integer
     */
    public $status;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSource('edu_user_capacity');
    }

    public function onConstruct()
    {
        $this->status = new RawValue('default');
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
            'capacity_all' => 'capacity_all',
            'capacity_used' => 'capacity_used',
            'status' => 'status',
        );
    }

}
