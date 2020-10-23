<?php

use Phalcon\Mvc\Router;
use Phalcon\Mvc\Router\Annotations as RouterAnnotations;

$router = new RouterAnnotations(false);
$router->removeExtraSlashes(true);

/** 
 * Include Routes
 */
$routes = glob(__DIR__ . '/routes/*.php');
foreach($routes as $fileRouter)
    $router->mount(require $fileRouter);

$router->add( '/404', [ 'controller' => 'Pages\PageErrors', 'action'     => 'e404', ]);
$router->notFound(['controller' => 'Pages\PageErrors','action'=> 'e404',]);


return $router;