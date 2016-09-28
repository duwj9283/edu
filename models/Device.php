<?php

namespace Cloud\Models;

class Device extends \Phalcon\Mvc\Model
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
     * @var string
     */
    public $ip;

    /**
     *
     * @var string
     */
    public $no;

    /**
     *
     * @var string
     */
    public $stream_name;

    /**
     *
     * @var string
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
     *
     * @var integer
     */
    public $rtmp_status;

    /**
     *
     * @var integer
     */
    public $record_status;

    /**
     *
     * @var integer
     */
    public $record_time;

    /**
     *
     * @var integer
     */
    public $volume;

    /**
     *
     * @var integer
     */
    public $win_type;

    /**
     *
     * @var integer
     */
    public $subtitle_status;

    /**
     *
     * @var string
     */
    public $subtitle_color;

    /**
     *
     * @var integer
     */
    public $subtitle_fam_id;

    /**
     *
     * @var string
     */
    public $subtitle_txt;

    /**
     *
     * @var string
     */
    public $record_name;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSource("edu_device");
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'edu_device';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Device[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Device
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
            'ip' => 'ip',
            'no' => 'no',
            'stream_name' => 'stream_name',
            'status' => 'status',
            'created_at' => 'created_at',
            'updated_at' => 'updated_at',
            'rtmp_status' => 'rtmp_status',
            'record_status' => 'record_status',
            'record_time' => 'record_time',
            'volume' => 'volume',
            'win_type' => 'win_type',
            'subtitle_status' => 'subtitle_status',
            'subtitle_color' => 'subtitle_color',
            'subtitle_fam_id' => 'subtitle_fam_id',
            'subtitle_txt' => 'subtitle_txt',
            'record_name' => 'record_name'
        );
    }
}
