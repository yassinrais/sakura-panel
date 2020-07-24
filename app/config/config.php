<?php

/*
 * Modified: prepend directory path of current file, because of this file own different ENV under between Apache and command line.
 * NOTE: please remove this comment.
 */
defined('BASE_PATH') || define('BASE_PATH', getenv('BASE_PATH') ?: realpath(dirname(__FILE__) . '/../..'));
defined('APP_PATH') || define('APP_PATH', BASE_PATH . '/app');

return new \Phalcon\Config([
    'database' => [
        'adapter'     => getenv('DB_ADAPTER')   ?: 'Mysql',
        'host'        => getenv('DB_HOST')      ?: 'localhost',
        'username'    => getenv('DB_USER')      ?: 'root',
        'password'    => getenv('DB_PASS')      ?: '',
        'dbname'      => getenv('DB_NAME')      ?: 'test',
        'charset'     => getenv('DB_CHARSET')   ?: 'utf8',
    ],
    'application' => [
        'appDir'         => APP_PATH . '/',
        'controllersDir' => APP_PATH . '/controllers/',
        'modelsDir'      => APP_PATH . '/models/',
        'migrationsDir'  => APP_PATH . '/migrations/',
        'viewsDir'       => APP_PATH . '/views/',
        'pluginsDir'     => APP_PATH . '/plugins/',
        'libraryDir'     => APP_PATH . '/library/',
        'cacheDir'       => BASE_PATH . '/cache/',
        'cacheViewsDir'       => BASE_PATH . '/cache/views/',
        'cacheSessionsDir' => BASE_PATH . '/cache/sessions/',
        'baseUri'        => '/',
        'baseURL'        => ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://"). $_SERVER['HTTP_HOST'] . "/",
    ]
]);
