<?php

namespace Cloud\Models;

class Groupdynamic extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $did;

    /**
     *
     * @var integer
     */
    public $gid;

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
    public $add_time;

	/**
	 *
	 * @var integer
	 */
	public $likeCounter;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSource("'edu_group_dynamic'");
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'edu_group_dynamic';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Groupdynamic[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Groupdynamic
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }
}
