<?php
namespace Sakura\Models\Acl;

use Sakura\Models\ModelBase;

/** 
 * Accesses == Actions
 */
class Accesses extends ModelBase
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
    public $resource_id;

    /**
     *
     * @var string
     */
    public $name;

    /**
     *
     * @var string
     */
    public $description;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSource($this->getSourceByName("resources_accesses"));
        

        $this->hasOne(
            'id',
            Resources::class,
            'resource_id',
            [
                'alias'=> 'resource',
                'reusable'=> true
            ]
        );

        $this->hasMany(
            'id',
            Permissions::class,
            'access_id',
            [
                'alias'=> 'permissions',
                'reusable'=> true
            ]
        );

    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return ResourcesAccesses[]|ResourcesAccesses|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null): \Phalcon\Mvc\Model\ResultsetInterface
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return ResourcesAccesses|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
