<?php

namespace Cloud\Models;
use Phalcon\Db\RawValue;
class Subject extends \Phalcon\Mvc\Model
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
    public $subject_code;

    /**
     *
     * @var string
     */
    public $subject_name;

    /**
     *
     * @var integer
     */
    public $father_id;

    /**
     *
     * @var integer
     */
    public $visible;

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
        $this->setSource('edu_subject');
        $this->belongsTo('father_id', 'Cloud\Models\Subject', 'id', array('alias'=>'FatherSubject'));
    }

    public function onConstruct()
    {
        $this->visible = new RawValue('default');
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'edu_subject';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Subject[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Subject
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
            'subject_code' => 'subject_code',
            'subject_name' => 'subject_name',
            'father_id' => 'father_id',
            'visible' => 'visible',
            'addtime' => 'addtime'
        );
    }

}
