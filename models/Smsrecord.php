<?php

namespace Cloud\Models;
use Phalcon\Db\RawValue;
class Smsrecord extends \Phalcon\Mvc\Model
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
    public $phone;

    /**
     *
     * @var string
     */
    public $message;

    /**
     *
     * @var string
     */
    public $type;

    /**
     *
     * @var integer
     */
    public $create_time;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSource('edu_sms_record');
    }
    public function onConstruct()
    {
        $this->type = new RawValue('default');
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
            'phone' => 'phone',
            'message' => 'message',
            'type' => 'type',
            'create_time' => 'create_time'
        );
    }

}
