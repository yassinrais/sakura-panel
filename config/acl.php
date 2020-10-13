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

$acl->setDefaultAction(Enum::ALLOW);




//  return acl instance
return $acl;