<?php

$router = $di->getRouter();

// Define your routes here


$configs = $di->getConfig();


if ($configs->route_groups)
foreach ($configs->route_groups as $prefix => $rgroups) {
    foreach ($rgroups as $name => $page) {
        $urls = is_object($page->url) ? $page->url : (object) [$page->url];
        foreach ($urls as $url) {
            $route = [
                'controller' => $page->controller ,
                'action'     => (!empty($page->action)) ? $page->action : 'index',
                'params'     => (!empty($page->params)) ? $page->params : null,
            ];
            foreach(['namespace'] as $key){
                if(!empty($page->$key))
                    $route[$key] = $page->$key;
            }
            $router->add(
                str_replace("@", $name,  str_replace("#", $prefix, $url)),
                $route
            );
        }
    }
}


$router->handle($_SERVER['REQUEST_URI'] ?? '/');
