<?php 

/** 
 * Define the Base/App Path
 */
defined('BASE_PATH') || define('BASE_PATH', getenv('BASE_PATH') ?: realpath(dirname(__FILE__) . '/../'));
defined('APP_PATH') || define('APP_PATH', BASE_PATH . '/app');

return include 'application.php';