<?php 
/**
 * Acl For Roles (Admins / Members / Guests)
 */
use Phalcon\Acl\Enum;
use Phalcon\Acl\Role;
use Phalcon\Acl\Component;
use Phalcon\Acl\Exception as AclException;

use Sakura\Library\RoleMemory as Memory;
use Sakura\Models\Acl\Roles;
use Sakura\Models\Acl\Permissions;
use Sakura\Models\Acl\Accesses;
use Sakura\Models\Acl\Resources;

$configs = $di->getConfig();

    
$acl = new Memory();

/** 
 * General Roles
 */
$roleAdmins     = new Role('admins', 'Administrator');
$roleMembers    = new Role('members', 'Members'); 
$roleGuests     = new Role('guests', 'Guests'); 

$acl->addRole($roleAdmins);
$acl->addRole($roleMembers);
$acl->addRole($roleGuests);

$acl->setDefaultAction(Enum::DENY);

$db_roles = Roles::find([
    'status = ?0',
    'bind'=>[
        Roles::ACTIVE
    ]
]);

foreach($db_roles as $role){
    $aclRole = new Role($role->name , $role->description);

    $acl->addRole($aclRole);
}

/**
 * Add Resources
 * To ACL
 * From DB
 */

$db_resources = Resources::find([
    'status = ?0',
    'bind'=>[
        Resources::ACTIVE
    ]
]);
    
foreach($db_resources as $resource){
    $compenent = new Component($resource->name, $resource->description);

    $actions = $resource->accesses->toArray() ?? [];

    foreach($actions as $access){
        $acl->addComponent(
            $compenent,
            $access["name"]
        );

    }
}

/**
 * Add Permissions
 * To ACL
 * From DB
 */

 $db_permissions = Permissions::find([
     'status = ?0',
     'bind'=>[
         Permissions::ACTIVE
     ]
 ]);


 foreach($db_permissions as $permission){
   
    
    $acl->allow(
        $permission->role->name,
        $permission->resource->name,
        $permission->access->name,
    );
 }


/** 
 * Add Resources
 * To ACL
 * From Configs
 */
if  (!empty($configs->acl->public_resources)){
    foreach($configs->acl->public_resources as $resource){

        // create & add component
        $compenent = new Component($resource->name , $resource->description);

        // // allow access
        if (!empty($resource->access))
            foreach($resource->access as $access){
                $acl->addComponent($compenent , $access);

                foreach(
                    (!empty($resource->roles) && $resource->roles !== "*") ? 
                        ( 
                            is_string($resource->roles) ? 
                                explode("|",$resource->roles) : 
                                $resource->roles
                        )  : 
                        ['*']
                    as $role){
                        $acl->allow( is_string($role) ? $role : $role->getName() , $resource->name, $access);
                    }
                    
            }
    }
}

//  return acl instance
return $acl;