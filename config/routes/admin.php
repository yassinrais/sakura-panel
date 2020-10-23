<?php

use Phalcon\Mvc\Router;
use Phalcon\Mvc\Router\Group as RouterGroup;

$group = new RouterGroup(
    [
        'namespace' => 'Sakura\Controllers\Admin',
    ]
);

/** 
 * Prefix
 */
$group->setPrefix('/admin');

/** 
 * Routes
 */

$group->add( '', [
    'controller'    => 'Dashboard',
    'action' => 'index'
]);

$group->add( '/:controller', [
    'controller'    => 1,
]);

$group->add( '/:controller/:action', [
    'controller'    => 1,
    'action'        => 2,
]);

/** 
 * Params
 */
$group->add( '/:controller/:action/:params', [
    'controller'    => 1,
    'action'        => 2,
    'params'        => 3,
]);

/** 
 * Plugins
 */

$group->add('/plugins/:action',[
    'namespace'     => 'Sakura\Plugins',
    'controller'    => 'Pluginsmanager\Controllers\Plugins',
    'action'        => 1
]);



return $group;