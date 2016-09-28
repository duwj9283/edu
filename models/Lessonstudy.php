<?php

namespace Cloud\Models;
use Phalcon\Db\RawValue;
class Lessonstudy extends \Phalcon\Mvc\Model
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
    public $lesson_id;

    /**
     *
     * @var integer
     */
    public $lesson_list_id;

    /**
     *
     * @var integer
     */
    public $study_status;

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
        $this->setSource('edu_lesson_study');
        $this->belongsTo('lesson_id', 'Cloud\Models\Lesson', 'id', array('alias'=>'Lesson'));
        $this->belongsTo('uid', 'Cloud\Models\Userinfo', 'uid', array('alias'=>'UserInfo'));
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'edu_lesson_study';
    }

    public function onConstruct()
    {
        $this->study_status = new RawValue('default');
        $this->addtime = new RawValue('default');
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Lessonstudy[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Lessonstudy
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
            'uid' => 'uid',
            'lesson_id' => 'lesson_id',
            'lesson_list_id' => 'lesson_list_id',
            'study_status' => 'study_status',
            'addtime' => 'addtime'
        );
    }

}
