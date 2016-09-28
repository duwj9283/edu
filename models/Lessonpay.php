<?php

namespace Cloud\Models;
use Phalcon\Db\RawValue;
class Lessonpay extends \Phalcon\Mvc\Model
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
     * @var double
     */
    public $amount;

    /**
     *
     * @var integer
     */
    public $vocher;

    /**
     *
     * @var double
     */
    public $money;

    /**
     *
     * @var integer
     */
    public $payment;

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
        $this->setSource("edu_lesson_pay");
    }

    public function onConstruct()
    {
        $this->addtime = new RawValue('default');
        $this->money = new RawValue('default');
        $this->amount = new RawValue('default');
        $this->vocher = new RawValue('default');
        $this->payment = new RawValue('default');
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'edu_lesson_pay';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Lessonpay[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Lessonpay
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
            'amount' => 'amount',
            'vocher' => 'vocher',
            'money' => 'money',
            'payment' => 'payment',
            'addtime' => 'addtime'
        );
    }

}
