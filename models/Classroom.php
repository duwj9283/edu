<?php

namespace Cloud\Models;

class Classroom extends \Phalcon\Mvc\Model
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
    public $title;

    /**
     *
     * @var integer
     */
    public $device_id;


    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSource('edu_class_room');
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Lessonshare[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Lessonshare
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
            'title' => 'title',
            'device_id' => 'device_id'
        );
    }

}
