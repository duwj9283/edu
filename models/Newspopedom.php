<?php

namespace Cloud\Models;

class Newspopedom extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var string
     */
    public $class_id;

    /**
     *
     * @var integer
     */
    public $role_id;

    /**
     *
     * @var integer
     */
    public $popedom;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSource("edu_news_popedom");
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'edu_news_popedom';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Newspopedom[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Newspopedom
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
            'class_id' => 'class_id',
            'role_id' => 'role_id',
            'popedom' => 'popedom'
        );
    }

}
