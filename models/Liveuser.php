<?php
namespace Cloud\Models;
use Phalcon\Db\RawValue;
class Liveuser extends \Phalcon\Mvc\Model
{
    /**
     *
     * @var integer
     */
    public $live_id;

    /**
     *
     * @var integer
     */
    public $uid;

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
        $this->setSource('edu_live_user');
    }

    public function onConstruct()
    {
        $this->addtime = new RawValue('default');
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'edu_live_user';
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
            'uid' => 'uid',
            'live_id' => 'live_id',
            'addtime' => 'addtime'
        );
    }

}
