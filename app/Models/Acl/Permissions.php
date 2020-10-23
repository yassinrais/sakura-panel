<?php
namespace Sakura\Models\Acl;

use Sakura\Models\ModelBase;

class Permissions extends ModelBase
{

    /**
     *
     * @var integer
     */
    public $role_id;

    /**
     *
     * @var integer
     */
    public $controller_id;

    /**
     *
     * @var integer
     */
    public $access_id;

    /**
     *
     * @var integer
     */
    public $allowed;

    /**
     *
     * @var integer
     */
    public $status;
    

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSource($this->getSourceByName("role_permissions"));
        

        $this->hasOne(
            'controller_id',
            Resources::class,
            'id',
            [
                'alias'=>'resource',
                'reusable'=> true
            ]
        );

        $this->hasOne(
            'access_id',
            Accesses::class,
            'id',
            [
                'alias'=>'access',
                'reusable'=> true
            ]
        );

        $this->hasOne(
            'role_id',
            Roles::class,
            'id',
            [
                'alias'=>'role',
                'reusable'=> true
            ]
        );

    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return RolePermissions[]|RolePermissions|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null): \Phalcon\Mvc\Model\ResultsetInterface
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return RolePermissions|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
