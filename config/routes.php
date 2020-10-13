<?php

use Phalcon\Mvc\Router;
use Phalcon\Mvc\Router\Annotations as RouterAnnotations;

$router = new RouterAnnotations(false);


$router->add(
    '/',
    [
        'controller' => 'index',
        'action'     => 'index',
    ]
);

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

$router->notFound(['controller' => 'Pages\PageErrors','action'=> 'e404',]);


return $router;