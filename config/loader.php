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
	'SakuraPanel\\Library'=>APP_PATH. '/library/',

	/**
	 *	Library
	 */
	'SakuraPanel\\Plugins'=>APP_PATH. '/plugins/',

	/**
	 *	Models
	 */
	'SakuraPanel\\Models' => APP_PATH . '/models/',


	/**	
	 * Controllers
	 */
	'SakuraPanel\\Controllers' => APP_PATH . '/controllers/',

	/**
	 * Form - Views
	 */
	'SakuraPanel\\Forms' => APP_PATH . '/forms/',


))->register();


