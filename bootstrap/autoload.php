<?php

use Phalcon\Loader;

/** 
 * Define the Base/App Path
 */
defined('BASE_PATH') || define('BASE_PATH', getenv('BASE_PATH') ?: realpath(dirname(__FILE__) . '/../'));
defined('APP_PATH') || define('APP_PATH', BASE_PATH . '/app');


/**
 * We're a registering a set of namespaces
 */
$loader = new Loader();
$loader->registerNamespaces(['Sakura' => dirname(dirname(__FILE__)) . '/app/']);
$loader->register();