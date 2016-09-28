<?php

namespace Cloud\Models;

class Newsinfo extends \Phalcon\Mvc\Model
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
    public $class_id;

    /**
     *
     * @var integer
     */
    public $sortnum;

    /**
     *
     * @var string
     */
    public $title;

    /**
     *
     * @var string
     */
    public $subtitle;

    /**
     *
     * @var string
     */
    public $title_color;

    /**
     *
     * @var string
     */
    public $title_bold;

    /**
     *
     * @var string
     */
    public $first_letter;

    /**
     *
     * @var string
     */
    public $website;

    /**
     *
     * @var string
     */
    public $tags;

    /**
     *
     * @var string
     */
    public $author;

    /**
     *
     * @var string
     */
    public $editor;

    /**
     *
     * @var string
     */
    public $source;

    /**
     *
     * @var string
     */
    public $publish_at;

    /**
     *
     * @var string
     */
    public $intro;

    /**
     *
     * @var string
     */
    public $content;

    /**
     *
     * @var string
     */
    public $pic1;

    /**
     *
     * @var string
     */
    public $pic2;

    /**
     *
     * @var string
     */
    public $file1;

    /**
     *
     * @var integer
     */
    public $is_top;

    /**
     *
     * @var integer
     */
    public $is_new;

    /**
     *
     * @var integer
     */
    public $is_hot;

    /**
     *
     * @var integer
     */
    public $is_recommend;

    /**
     *
     * @var integer
     */
    public $is_locked;

    /**
     *
     * @var integer
     */
    public $views;

    /**
     *
     * @var integer
     */
    public $comments;

    /**
     *
     * @var integer
     */
    public $status;

    /**
     *
     * @var integer
     */
    public $created_user_id;

    /**
     *
     * @var integer
     */
    public $updated_user_id;

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
        $this->setSource("edu_news_info");
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'edu_news_info';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Newsinfo[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Newsinfo
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
            'class_id' => 'class_id',
            'sortnum' => 'sortnum',
            'title' => 'title',
            'subtitle' => 'subtitle',
            'title_color' => 'title_color',
            'title_bold' => 'title_bold',
            'first_letter' => 'first_letter',
            'website' => 'website',
            'tags' => 'tags',
            'author' => 'author',
            'editor' => 'editor',
            'source' => 'source',
            'publish_at' => 'publish_at',
            'intro' => 'intro',
            'content' => 'content',
            'pic1' => 'pic1',
            'pic2' => 'pic2',
            'file1' => 'file1',
            'is_top' => 'is_top',
            'is_new' => 'is_new',
            'is_hot' => 'is_hot',
            'is_recommend' => 'is_recommend',
            'is_locked' => 'is_locked',
            'views' => 'views',
            'comments' => 'comments',
            'status' => 'status',
            'created_user_id' => 'created_user_id',
            'updated_user_id' => 'updated_user_id',
            'created_at' => 'created_at',
            'updated_at' => 'updated_at'
        );
    }

}
