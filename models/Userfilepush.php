<?php
namespace Cloud\Models;
use Phalcon\Db\RawValue;
class Userfilepush extends \Phalcon\Mvc\Model
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
    public $subject_id;

    /**
     *
     * @var integer
     */
    public $file_type;

    /**
     *
     * @var integer
     */
    public $status;

    /**
     *
     * @var string
     */
    public $push_file_name;

    /**
     *
     * @var string
     */
    public $push_date_folder;

    /**
     *
     * @var string
     */
    public $verifyer;

    /**
     *
     * @var string
     */
    public $verifytime;

    /**
     *
     * @var string
     */
    public $fail_reason;

    /**
     *
     * @var string
     */
    public $addtime;

    public function setAddtime()
    {
        $this->addtime = date('Y-m-d H:i:s');
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSource('edu_user_file_push');
        $this->belongsTo('uid', 'Cloud\Models\Userinfo', 'uid', array('alias'=>'UserInfo'));
    }

    public function onConstruct()
    {
        $this->status = new RawValue('default');
        $this->verifytime = new RawValue('default');
        $this->verifyer = new RawValue('default');
        $this->fail_reason = new RawValue('default');
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
            'file_share' => 'file_share',
            'file_download' => 'file_download',
            'subject_id' => 'subject_id',
            'file_type' => 'file_type',
            'status' => 'status',
            'push_file_name' => 'push_file_name',
            'push_date_folder' => 'push_date_folder',
            'verifytime' => 'verifytime',
            'verifyer' => 'verifyer',
            'fail_reason' => 'fail_reason',
            'addtime' => 'addtime'
        );
    }

}
