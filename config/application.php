<?php

/**
 * vendor include_once 
 */
include_once BASE_PATH . '/vendor/autoload.php';

/**
 * Env Loader
 */
$dotenv = Dotenv\Dotenv::createImmutable(BASE_PATH);
$dotenv->load();


/**
 * Set Configs
 */

$configs = array(
    'database' => [
        'adapter'     => getenv('DB_ADAPTER')   ?: 'Mysql',
        'host'        => getenv('DB_HOST')      ?: 'localhost',
        'username'    => getenv('DB_USER')      ?: 'root',
        'password'    => getenv('DB_PASS')      ?: '',
        'dbname'      => getenv('DB_NAME')      ?: '',
        'charset'     => getenv('DB_CHARSET')   ?: 'utf8',
    ],
    'application' => [
        'appDir'         => APP_PATH . '/',
        'controllersDir' => APP_PATH . '/controllers/',
        'modelsDir'      => APP_PATH . '/models/',
        'migrationsDir'  => APP_PATH . '/migrations/',
        'pluginsDir'     => APP_PATH . '/plugins/',
        'libraryDir'     => APP_PATH . '/library/',
        'formsDir'       => APP_PATH . '/forms/',
        'translateDir'   => APP_PATH . '/translations/',
        
        'viewsDir'       => APP_PATH . '/views/default/',
        'widgetsPath'    => APP_PATH. '/views/default/widgets/',

        'compiledSeparator'=>'_',

        'baseUri'        => '/',
        // server configs  doesnt exist in cli ("request" :@ to ignore undefined vars) 
        'baseURL'        => ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || @$_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://"). @$_SERVER['HTTP_HOST'] . "/",
    ],
    'theme'=>[
        'path'=> BASE_PATH . '/public/assets/custom/',
        'uri'=> 'assets/custom/',
    ],
);




/**
 *      Logger
 */
include_once ('inc/logger.inc.php');


/**
 *      Tables
 */
include_once ('inc/tables.inc.php');


/**
 *      Routers
 */
include_once ('inc/routes.inc.php');



/**
 *      Mailer
 */
include_once ('inc/mail.inc.php');



/**
 *      Security
 */
include_once ('inc/security.inc.php');


/**
 *      Cache
 */
include_once ('inc/cache.inc.php');



/**
 *      Menu
 */
include_once ('inc/menu.inc.php');


// return configs
return new \Phalcon\Config($configs);
