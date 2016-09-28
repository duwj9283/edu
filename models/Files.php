<?php

namespace Cloud\Models;
use Phalcon\Db\RawValue;
class Files extends \Phalcon\Mvc\Model
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
    public $size;

    /**
     *
     * @var string
     */
    public $md5;

    /**
     *
     * @var string
     */
    public $path;

    /**
     *
     * @var string
     */
    public $video_thumb;

    /**
     *
     * @var integer
     */
    public $file_count;

    /**
     *
     * @var integer
     */
    public $addtime;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSource('edu_files');
    }

    public function onConstruct()
    {
        $this->video_thumb = new RawValue('default');
        $this->file_count = new RawValue('default');
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
            'size' => 'size',
            'md5' => 'md5',
            'path' => 'path',
            'video_thumb' => 'video_thumb',
            'file_count' => 'file_count',
            'addtime' => 'addtime'
        );
    }
}
