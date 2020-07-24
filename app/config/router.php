<?php

$router = $di->getRouter();

// Define your routes here


$router->handle($_SERVER['REQUEST_URI']);


$configs = $di->getConfig();


if ($configs->sections)
foreach ($configs->sections as $prefix => $sections) {
    foreach ($sections as $name => $page) {
        $urls = is_object($page->url) ? $page->url : (object) [$page->url];
        foreach ($urls as $url) {
            $router->add(
                str_replace("@", $name,  str_replace("#", $prefix, $url)),
                [
                    'controller' => 
                        str_replace('[M]', 'Sakura\Controllers\Member', 
                            $page->controller
                        ),
                    'action'     => (!empty($page->action)) ? $page->action : 'index',
                    'params'     => (!empty($page->params)) ? $page->params : null,
                ]
            );
        }
    }
}


$router->handle($_SERVER['REQUEST_URI']);
