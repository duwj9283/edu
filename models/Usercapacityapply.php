<?php

namespace Cloud\Models;
use Phalcon\Db\RawValue;
class Usercapacityapply extends \Phalcon\Mvc\Model
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
    public $reason;

    /**
     *
     * @var string
     */
    public $fail_reason;

    /**
     *
     * @var integer
     */
    public $capacity;

    /**
     *
     * @var integer
     */
    public $status;

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
        $this->setSource('edu_user_capacity_apply');
    }

    public function onConstruct()
    {
        $this->status = new RawValue('default');
        $this->capacity = new RawValue('default');
        $this->fail_reason = new RawValue('default');
        $this->addtime = new RawValue('default');
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
            'reason' => 'reason',
            'fail_reason' => 'fail_reason',
            'capacity' => 'capacity',
            'status' => 'status',
            'addtime' => 'addtime'

        );
    }

}
