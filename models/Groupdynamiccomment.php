<?php

namespace Cloud\Models;

class Groupdynamiccomment extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $cid;

    /**
     *
     * @var integer
     */
    public $uid;

    /**
     *
     * @var integer
     */
    public $did;

    /**
     *
     * @var integer
     */
    public $refcid;

    /**
     *
     * @var integer
     */
    public $tuid;

    /**
     *
     * @var integer
     */
    public $content;

    /**
     *
     * @var integer
     */
    public $add_time;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSource("'edu_group_dynamic_comment'");
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'edu_group_dynamic_comment';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Groupdynamiccomment[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Groupdynamiccomment
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
            'cid' => 'cid',
            'uid' => 'uid',
            'did' => 'did',
            'refcid' => 'refcid',
            'tuid' => 'tuid',
            'content' => 'content',
            'add_time' => 'add_time'
        );
    }

}
