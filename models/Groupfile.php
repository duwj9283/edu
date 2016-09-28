<?php

namespace Cloud\Models;

class Groupfile extends \Phalcon\Mvc\Model
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
    public $gid;

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
     * @var string
     */
    public $group_file_name;

    /**
     *
     * @var string
     */
    public $addtime;

    /**
     *
     * @var string
     */
    public $group_date_folder;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSource('edu_group_file');
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'edu_group_file';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Groupfile[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Groupfile
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
            'gid' => 'gid',
            'uid' => 'uid',
            'user_file_id' => 'user_file_id',
            'group_file_name' => 'group_file_name',
            'group_date_folder' => 'group_date_folder',
            'addtime' => 'addtime'
        );
    }

}
