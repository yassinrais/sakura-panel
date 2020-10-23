<?php

use Phalcon\Mvc\Router;
use Phalcon\Mvc\Router\Annotations as RouterAnnotations;

$router = new RouterAnnotations(false);

$router->add(
    '/auth/:action',
    [
        'controller' => 'Auth\Auth',
        'action'     => 1,
    ]
);
$router->add(
    '/404',
    [
        'controller' => 'Pages\PageErrors',
        'action'     => 'e404',
    ]
);

$router->add(
    '/member/:controller',
    [
        'namespace' => 'Sakura\Controllers\Member',
        'controller'     => 1,
    ]
);

$router->notFound(['controller' => 'Pages\PageErrors','action'=> 'e404',]);


return $router;