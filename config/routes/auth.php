<?php

use Phalcon\Mvc\Router;
use Phalcon\Mvc\Router\Group as RouterGroup;

$group = new RouterGroup(
    [
        'controller' => 'Auth\Auth',
    ]
);

/** 
 * Prefix
 */
$group->setPrefix('/auth');

/** 
 * Routes
 */

$group->add( '/:action', [
    'action'     => 1,
]);



return $group;