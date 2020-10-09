<?php 
/**
 * Acl For Roles (Admins / Members / Guests)
 */
use Phalcon\Acl\Role;
use Phalcon\Acl\Component;
use Phalcon\Acl\Exception as AclException;

use Sakura\Library\RoleMemory as Memory;
use Sakura\Models\Security\Roles;

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

$acl->setDefaultAction(\Phalcon\Acl\Enum::DENY);

if ($configs->route_groups)
foreach ($configs->route_groups as $prefix => $rgroup) {
    foreach ($rgroup as $name => $page) {
        if (!$page->access)
            break;

        $acl_item = is_object($page->access) ? $page->access : [$page->access];

        foreach ($acl_item as $acl_names => $actions) {
            if (is_int($acl_names)){
                // check if  array is associative or sequential
                $acl_names = $actions;
                $actions = ["*"];
            }
            
            $urls = is_object($page->url) ? $page->url : (object) [$page->url];
            
            $controller = is_numeric($page->controller) ? (($page->namespace ?: "")) : $page->controller  ;

            $roles_alloweds = explode("|", $acl_names ?: "*");

            $actions = is_object($actions) && method_exists($actions , 'toArray') ? $actions->toArray() : $actions;

            $actions = is_array($actions) && !empty($actions) ? $actions : [$actions ?: '*'];

            $acl->addComponent(
                $controller,
                $actions
            );

            foreach ($roles_alloweds as $name)
                $acl->allow($name , $controller , $actions);
        }
    }
}



/** 
 * Custom Roles From Database
 */
$roles = Roles::find([
    'status = ?0',
    'bind'=>[ Roles::ACTIVE ],
]);

foreach($roles as $row){
    $role = new Role($row->name , $row->title);
    
    if (!empty($row->type))
        $acl->addRole($role , $row->type);
}



//  return acl instance
return $acl;