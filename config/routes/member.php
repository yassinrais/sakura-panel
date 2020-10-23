<?php

use Phalcon\Mvc\Router;
use Phalcon\Mvc\Router\Group as RouterGroup;

$group = new RouterGroup(
    [
        'namespace' => 'Sakura\Controllers\Member',
        'controller' => 'Dashboard',
    ]
);

/** 
 * Prefix
 */
$group->setPrefix('/member');

/** 
 * Routes
 */

$group->add( '/:controller', [
    'controller'    => 1,
]);

$group->add( '/:controller/:action', [
    'controller'    => 1,
    'action'        => 2,
]);



return $group;