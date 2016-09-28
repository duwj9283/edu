<?php

namespace Cloud\Models;
use Phalcon\Db\RawValue;
class Converttask extends \Phalcon\Mvc\Model
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
    public $table_name;

    /**
     *
     * @var string
     */
    public $field_name;

    /**
     *
     * @var integer
     */
    public $master_id;

    /**
     *
     * @var string
     */
    public $file_path;

    /**
     *
     * @var string
     */
    public $target_path;

    /**
     *
     * @var integer
     */
    public $need_upload;

    /**
     *
     * @var integer
     */
    public $need_convert;

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
        $this->setSource("edu_convert_task");
    }

    public function onConstruct()
    {
        $this->status = new RawValue('default');
        $this->need_upload = new RawValue('default');
        $this->need_convert = new RawValue('default');
        $this->created_at = new RawValue('default');
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'edu_convert_task';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Converttask[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Converttask
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
            'table_name' => 'table_name',
            'field_name' => 'field_name',
            'master_id' => 'master_id',
            'file_path' => 'file_path',
            'target_path' => 'target_path',
            'need_upload' => 'need_upload',
            'need_convert' => 'need_convert',
            'status' => 'status',
            'created_at' => 'created_at',
            'updated_at' => 'updated_at'
        );
    }

}
