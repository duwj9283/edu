<?php
namespace Cloud\Models;
use Phalcon\Db\RawValue;
class Userfileshare extends \Phalcon\Mvc\Model
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
    public $user_file_id;

    /**
     *
     * @var integer
     */
    public $uid;

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
        $this->setSource('edu_user_file_share');
    }

    public function onConstruct()
    {
        $this->status = new RawValue('default');
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
            'user_file_id' => 'user_file_id',
            'uid' => 'uid',
            'status' => 'status',
            'addtime' => 'addtime'
        );
    }

}
