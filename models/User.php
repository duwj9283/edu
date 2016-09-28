<?php

namespace Cloud\Models;
use Phalcon\Db\RawValue;

class User extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $uid;

    /**
     *
     * @var string
     */
    public $user_token;

    /**
     *
     * @var string
     */
    public $username;

    /**
     *
     * @var string
     */
    public $password;

    /**
     *
     * @var string
     */
    public $phone;

    /**
     *
     * @var string
     */
    public $email;


    /**
     *
     * @var integer
     */
    public $login_count;

    /**
     *
     * @var string
     */
    public $login_time;

    /**
     *
     * @var string
     */
    public $login_ip;

    /**
     *
     * @var string
     */
    public $reg_time;

    /**
     *
     * @var integer
     */
    public $role_id;
    /**
     *
     * @var integer
     */
    public $disable;

    /**
     *
     * @var integer
     */
    public $is_forget;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSource('edu_user');
        $this->useDynamicUpdate(true);
        $this->setup(array("notNullValidations"=>false));
    }

    public function onConstruct()
    {
        $this->phone = new RawValue('default');
        $this->email = new RawValue('default');
        $this->login_time = new RawValue('default');
        $this->login_ip = new RawValue('default');
        $this->login_count = new RawValue('default');
        $this->role_id = new RawValue('default');
        $this->disable = new RawValue('default');
        $this->is_forget = new RawValue('default');
        $this->reg_time = date('Y-m-d H:i:s', time());
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
            'uid' => 'uid',
            'user_token' => 'user_token',
            'username' => 'username',
            'password' => 'password',
            'phone' => 'phone',
            'email' => 'email',
            'login_count' => 'login_count',
            'login_time' => 'login_time',
            'login_ip' => 'login_ip',
            'reg_time' => 'reg_time',
            'role_id' => 'role_id',
            'disable' => 'disable',
            'is_forget' => 'is_forget'
        );
    }
}
