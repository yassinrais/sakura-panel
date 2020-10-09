<?php

$loader = new \Phalcon\Loader();

/**
 * We're a registering a set of directories taken from the configuration file
 */
$loader->registerDirs(
    [
        $config->application->controllersDir,
        $config->application->modelsDir,
        $config->application->libaryDir,
        $config->application->pluginsDir,
    ]
)->register();


$loader->registerNamespaces(array(

	/**
	 *	Library
	 */
	'Sakura\\Library'=>APP_PATH. '/library/',

	/**
	 *	Library
	 */
	'Sakura\\Plugins'=>APP_PATH. '/plugins/',

	/**
	 *	Models
	 */
	'Sakura\\Models' => APP_PATH . '/models/',


	/**	
	 * Controllers
	 */
	'Sakura\\Controllers' => APP_PATH . '/controllers/',

	/**
	 * Form - Views
	 */
	'Sakura\\Forms' => APP_PATH . '/forms/',


))->register();


