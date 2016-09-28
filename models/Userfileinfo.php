<?php

namespace Cloud\Models;

class Userfileinfo extends \Phalcon\Mvc\Model
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
    public $subject_id;

    /**
     *
     * @var string
     */
    public $knowledge_point;

    /**
     *
     * @var integer
     */
    public $language;

    /**
     *
     * @var integer
     */
    public $application_type;

    /**
     *
     * @var string
     */
    public $desc;

    /**
     *
     * @var integer
     */
    public $addtime;

    public function setAddtime()
    {
        $this->addtime = date('Y-m-d H:i:s');
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSource("edu_user_file_info");
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'edu_user_file_info';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Userfileinfo[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Userfileinfo
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
            'user_file_id' => 'user_file_id',
            'subject_id' => 'subject_id',
            'knowledge_point' => 'knowledge_point',
            'language' => 'language',
            'application_type' => 'application_type',
            'desc' => 'desc',
            'addtime' => 'addtime'
        );
    }

}
