<?php

namespace Cloud\Models;
use Phalcon\Db\RawValue;
class Userfile extends \Phalcon\Mvc\Model
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
     * @var string
     */
    public $file_name;

    /**
     *
     * @var string
     */
    public $path;

    /**
     *
     * @var integer
     */
    public $file_from;

    /**
     *
     * @var integer
     */
    public $file_status;

    /**
     *
     * @var integer
     */
    public $file_share;

    /**
     *
     * @var integer
     */
    public $file_download;

    /**
     *
     * @var integer
     */
    public $file_type;

    /**
     *
     * @var integer
     */
    public $percent;

    /**
     *
     * @var integer
     */
    public $file_id;

    /**
     *
     * @var integer
     */
    public $file_size;

    /**
     *
     * @var string
     */
    public $file_md5;

    /**
     *
     * @var string
     */
    public $video_thumb;

    /**
     *
     * @var integer
     */
    public $visible;

    /**
     *
     * @var integer
     */
    public $del_type;

    /**
     *
     * @var integer
     */
    public $addtime;

    /**
     *
     * @var integer
     */
    public $download_count;

    /**
     *
     * @var integer
     */
    public $is_video;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSource('edu_user_file');
    }

    public function onConstruct()
    {
        $this->percent = new RawValue('default');
        $this->video_thumb = new RawValue('default');
        $this->file_size = new RawValue('default');
        $this->file_md5 = new RawValue('default');
        $this->visible = new RawValue('default');
        $this->del_type = new RawValue('default');
        $this->download_count = new RawValue('default');
        $this->is_video = new RawValue('default');
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
            'file_name' => 'file_name',
            'path' => 'path',
            'file_from' => 'file_from',
            'file_status' => 'file_status',
            'file_type' => 'file_type',
            'percent' => 'percent',
            'file_id' => 'file_id',
            'file_size' => 'file_size',
            'file_md5' => 'file_md5',
            'video_thumb' => 'video_thumb',
            'visible' => 'visible',
            'del_type' => 'del_type',
            'addtime' => 'addtime',
            'download_count' => 'download_count',
            'is_video' => 'is_video',
        );
    }

	public function getPathTmp(){
		return "/api/source/getImageThumb/".$this->id."/260/260";
	}

	public function getVideoTmp(){
		return "/api/source/getImageThumb/".$this->id."/550/260";
	}
}
