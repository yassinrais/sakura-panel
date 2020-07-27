<?php 

use Phalcon\Acl\Adapter\Memory;
use Phalcon\Acl\Component;
use Phalcon\Acl\Role;

$configs = $di->getConfig();

$acl = new Memory();

$roleAdmins     = new Role('admins', 'Administrator Access');
$roleMembers    = new Role('members', 'Members Access'); 
$roleGeusts     = new Role('geusts', 'Geusts Access'); 

$acl->addRole($roleAdmins);
$acl->addRole($roleMembers);
$acl->addRole($roleGeusts);

$acl->setDefaultAction(\Phalcon\Acl\Enum::DENY);

if ($configs->sections)
foreach ($configs->sections as $prefix => $sections) {
    foreach ($sections as $name => $page) {
        if (!$page->access)
            break;

        $acl_item = $page->access;
        foreach ($acl_item as $acl_names => $actions) {

            $urls = is_object($page->url) ? $page->url : (object) [$page->url];
            $controller =  str_replace('[M]', 'Sakura\Controllers\Member', $page->controller );
            $roles_alloweds = explode("|", $acl_names ?: "*");

            $actions = is_array($actions) && !empty($actions->toArray()) ? $actions->toArray() : ['*'];
    
            $acl->addComponent(
                $controller,
                $actions
            );

            foreach ($roles_alloweds as $name)
                $acl->allow($name , $controller , $actions);
        }
    }
        


}

return $acl;