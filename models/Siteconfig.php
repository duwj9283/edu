<?php

namespace Cloud\Models;

class Siteconfig extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var string
     */
    public $option_title;

    /**
     *
     * @var string
     */
    public $option_name;

    /**
     *
     * @var string
     */
    public $option_value;


    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSource("edu_siteconfig");
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'edu_siteconfig';
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
            'option_title' => 'option_title',
            'option_name' => 'option_name',
            'option_value' => 'option_value'
        );
    }

}
