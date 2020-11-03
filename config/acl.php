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


$acl->setDefaultAction(Enum::DENY);

/** 
 * Current Roles 
 */
foreach(['admins','members','guests'] as $roleName)
    Roles::findOrCreate(['name = :name:' , 'bind'=> ['name'=> $roleName]] , ['title'=> ucfirst($roleName),'description'=> ucfirst($roleName)]);

/** 
 * Others roles
 */
$db_roles = Roles::find([
    'status = ?0',
    'bind'=>[
        Roles::ACTIVE
    ]
]);

/** 
 * Roles Add
 * To ACL
 * From DB
 */
foreach($db_roles as $role){
    $aclRole = new Role($role->name , $role->description);

    $acl->addRole($aclRole);
}

/** 
 * Inherit Add
 * To ACL
 * From DB
 */
foreach($db_roles as $role){
    if ($role->inherit != null)
        try{
            $acl->addInherit($role->name,$role->inherit);
        }catch(Exception $e){
            $di->getLogger()->error($e->getMessage());
        }
}
/**
 * Resources Add
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
 * Permissions Add
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
   
    if (!empty($permission->resource) && !empty($permission->role) && !empty($permission->access))
    {    
        // check if user/guest to allowed or denied
        $allowed = $permission->allowed  == Permissions::ACTIVE ? "allow":"deny";

        // add deny/allow
        $acl->$allowed(
            $permission->role->name,
            $permission->resource->name,
            $permission->access->name,
            
        );
    }
    
 }


/** 
 * Resources Add
 * To ACL
 * From Configs
 */
if  (!empty($configs->acl->resources)){
    foreach($configs->acl->resources as $resource){

        // create & add component
        $compenent = new Component($resource->name);

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