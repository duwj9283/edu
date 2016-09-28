<?php

namespace Cloud\Models;

class Group extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $gid;

    /**
     *
     * @var string
     */
    public $name;

    /**
     *
     * @var string
     */
    public $headpic;

    /**
     *
     * @var integer
     */
    public $type;

    /**
     *
     * @var integer
     */
    public $open;

    /**
     *
     * @var integer
     */
    public $validate;

    /**
     *
     * @var string
     */
    public $intro;

    /**
     *
     * @var integer
     */
    public $creater;

    /**
     *
     * @var string
     */
    public $add_time;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSource("'edu_group'");
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'edu_group';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Group[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Group
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }
}
