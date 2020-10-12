<?php

use Phalcon\Mvc\Router;

$router = new Router();

/** 
 * TODO 
 * REPLACE ROUTE SYSTEM 
 * 
 * UPGRADE PLUGIN ROUTER
 * UPGRADE MEMBER MENU
 * UPGRADE PERMISSIONS
 * 
 * UPGRADE TO USE MULTI TEMPLATES 
 * 
 */
// if ($configs->route_groups)
// foreach ($configs->route_groups as $prefix => $rgroups) {
//     foreach ($rgroups as $name => $page) {
//         $urls = is_object($page->url) ? $page->url : (object) [$page->url];
//         foreach ($urls as $url) {
//             $route = [
//                 'controller' => $page->controller ,
//                 'action'     => (!empty($page->action)) ? $page->action : 'index',
//                 'params'     => (!empty($page->params)) ? $page->params : null,
//             ];
//             foreach(['namespace'] as $key){
//                 if(!empty($page->$key))
//                     $route[$key] = $page->$key;
//             }
//             $router->add(
//                 str_replace("@", $name,  str_replace("#", $prefix, $url)),
//                 $route
//             );
//         }
//     }
// }

$router->add('/test' , [
    'controller' => 'index',
    'action'=>'test'
]);

return $router;