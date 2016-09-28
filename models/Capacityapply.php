<?php

namespace Cloud\Models;

class Capacityapply extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $aid;

    /**
     *
     * @var integer
     */
    public $uid;

    /**
     *
     * @var string
     */
    public $reason;

    /**
     *
     * @var integer
     */
    public $apply_capacity;

    /**
     *
     * @var integer
     */
    public $status;

    /**
     *
     * @var integer
     */
    public $add_time;

    /**
     *
     * @var integer
     */
    public $proc_time;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSource("'edu_capacity_apply'");
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'edu_capacity_apply';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Capacityapply[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Capacityapply
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
