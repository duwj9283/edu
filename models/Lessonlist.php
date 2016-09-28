<?php

namespace Cloud\Models;
use Phalcon\Db\RawValue;
class Lessonlist extends \Phalcon\Mvc\Model
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
    public $lesson_id;

    /**
     *
     * @var string
     */
    public $name;

    /**
     *
     * @var string
     */
    public $path;

    /**
     *
     * @var integer
     */
    public $file;

    /**
     *
     * @var string
     */
    public $file_ids;

    /**
     *
     * @var string
     */
    public $question_ids;

    /**
     *
     * @var integer
     */
    public $sort;

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
        $this->setSource('edu_lesson_list');
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'edu_lesson_list';
    }

    public function onConstruct()
    {
        $this->addtime = new RawValue('default');
        $this->file = new RawValue('default');
        $this->file_ids = new RawValue('default');
        $this->question_ids = new RawValue('default');
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Lessonlist[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Lessonlist
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
            'lesson_id' => 'lesson_id',
            'name' => 'name',
            'path' => 'path',
            'file' => 'file',
            'file_ids' => 'file_ids',
            'question_ids' => 'question_ids',
            'sort' => 'sort',
            'addtime' => 'addtime'
        );
    }

}
