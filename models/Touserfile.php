<?php

namespace Cloud\Models;

class Touserfile extends \Phalcon\Mvc\Model
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
     * @var integer
     */
    public $user_file_id;

    /**
     *
     * @var integer
     */
    public $to_uid;

    /**
     *
     * @var string
     */
    public $user_file_name;

    /**
     *
     * @var string
     */
    public $user_date_folder;

    /**
     *
     * @var string
     */
    public $addtime;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSource("edu_to_user_file");
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'edu_to_user_file';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Touserfile[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Touserfile
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
            'user_file_id' => 'user_file_id',
            'to_uid' => 'to_uid',
            'user_file_name' => 'user_file_name',
            'user_date_folder' => 'user_date_folder',
            'addtime' => 'addtime'
        );
    }

}
