<?php
declare(strict_types=1);

use Phalcon\Di\FactoryDefault;

error_reporting(E_ALL);

define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . '/app');

$debug = new \Phalcon\Debug();
$debug->listen();


try {
    /**
     * vendor include 
     */
    include BASE_PATH . '/vendor/autoload.php';

    /**
     * Env Loader
     */
    $dotenv = Dotenv\Dotenv::createUnsafeImmutable(BASE_PATH);
    $dotenv->load();

    /**
     * The FactoryDefault Dependency Injector automatically registers
     * the services that provide a full stack framework.
     */
    $di = new FactoryDefault();

    /**
     * Read services
     */
    include APP_PATH . '/config/services.php';

    /**
     * Handle routes
     */
    include APP_PATH . '/config/router.php';

    /**
     * Get config service for use in inline setup below
     */
    $config = $di->getConfig();

    /**
     * Include Autoloader
     */
    include APP_PATH . '/config/loader.php';






    

    /**
     * Get plguins service for use in inline setup below
     */
    $di->getPlugins()->loadPlugins();


    /**
     * Handle the request
     */
    $application = new \Phalcon\Mvc\Application($di);

    echo $application->handle($_SERVER['REQUEST_URI'])->getContent();
} catch (\Exception $e) {
    if (getenv('APP_DEBUG') == true) {
        echo $e->getMessage() . '<br>';
        echo '<pre>' . $e->getTraceAsString() . '</pre>';
    }else 
        exit('Error ! please contact support ! ');
}
