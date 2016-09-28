<?php

namespace Cloud\Models;

class Messagestatus extends \Phalcon\Mvc\Model
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
    public $message_id;

    /**
     *
     * @var integer
     */
    public $sender_id;

    /**
     *
     * @var integer
     */
    public $receiver_id;

    /**
     *
     * @var integer
     */
    public $view_status;

    /**
     *
     * @var integer
     */
    public $status;

    /**
     *
     * @var string
     */
    public $created_at;

    /**
     *
     * @var string
     */
    public $updated_at;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSource("edu_message_status");
        $this->belongsTo('message_id', 'Cloud\Models\Message', 'id', array('alias'=>'Message'));
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'edu_message_status';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Messagestatus[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Messagestatus
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
            'message_id' => 'message_id',
            'sender_id' => 'sender_id',
            'receiver_id' => 'receiver_id',
            'view_status' => 'view_status',
            'status' => 'status',
            'created_at' => 'created_at',
            'updated_at' => 'updated_at'
        );
    }

}
