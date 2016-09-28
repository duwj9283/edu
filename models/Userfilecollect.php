<?php

namespace Cloud\Models;

class Userfilecollect extends \Phalcon\Mvc\Model
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
    public $user_file_id;

    /**
     *
     * @var integer
     */
    public $uid;

    /**
     *
     * @var string
     */
    public $addtime;

    /**
     *
     * @var string
     */
    public $collect_file_name;

    /**
     *
     * @var string
     */
    public $collect_date_folder;

    public function setAddtime()
    {
        $this->addtime = date('Y-m-d H:i:s');
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSource('edu_user_file_collect');
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
            'user_file_id' => 'user_file_id',
            'uid' => 'uid',
            'collect_file_name' => 'collect_file_name',
            'collect_date_folder' => 'collect_date_folder',
            'addtime' => 'addtime'
        );
    }

}
