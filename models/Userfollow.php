<?php

namespace Cloud\Models;

class Userfollow extends \Phalcon\Mvc\Model
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
    public $tuid;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSource("edu_user_follows");
        $this->belongsTo('uid', 'Cloud\Models\Userinfo', 'uid', array('alias'=>'UserInfo'));
        $this->belongsTo('tuid', 'Cloud\Models\Userinfo', 'uid', array('alias'=>'TUserInfo'));
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'edu_user_follows';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Userfollows[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Userfollows
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

    public function columnMap()
    {
        return array(
            'id' => 'id',
            'uid' => 'uid',
            'tuid' => 'tuid',
        );
    }
}
