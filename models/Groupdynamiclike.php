<?php

namespace Cloud\Models;

class Groupdynamiclike extends \Phalcon\Mvc\Model
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
    public $uid;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSource("'edu_group_dynamic_like'");
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'edu_group_dynamic_like';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Groupdynamiclike[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Groupdynamiclike
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
            'did' => 'did',
            'uid' => 'uid'
        );
    }

}
