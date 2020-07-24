<?php

$loader = new \Phalcon\Loader();

/**
 * We're a registering a set of directories taken from the configuration file
 */
$loader->registerDirs(
    [
        $config->application->controllersDir,
        $config->application->modelsDir
    ]
)->register();


$loader->registerNamespaces(array(

	/**
	 *	Library
	 */
	'SakuraPanel\Library'=>APP_PATH. '/library/',

	/**
	 *	Base Shared
	 */
	'SakuraPanel\Models' => APP_PATH . '/models/',
	'SakuraPanel\Controllers' => APP_PATH . '/controllers/',

	/**
	 *	App
	 */
	'SakuraPanel\Models\App' => APP_PATH . '/models/app/',
	'SakuraPanel\Controllers\App' => APP_PATH . '/controllers/app/',

	/**
	 *	Data
	 */
	'SakuraPanel\Models\Data' => APP_PATH . '/models/data/',
	'SakuraPanel\Controllers\Data' => APP_PATH . '/controllers/data/',

	/**
	 *	Role
	 */
	'SakuraPanel\Models\Role' => APP_PATH . '/models/role/',
	'SakuraPanel\Controllers\Role' => APP_PATH . '/controllers/role/',

	/**
	 *	Security
	 */
	'SakuraPanel\Models\Security' => APP_PATH . '/models/security/',
	'SakuraPanel\Controllers\Security' => APP_PATH . '/controllers/security/',

	/**
	 *	Member
	 */
	'SakuraPanel\Models\Member' => APP_PATH .'/models/member/',
	'SakuraPanel\Controllers\Member' => APP_PATH .'/controllers/member/',
		// :: user profile :: // 
		'SakuraPanel\Controllers\Member\Profile' => APP_PATH .'/controllers/member/profile',
		// :: shared controllers :: //
		'SakuraPanel\Controllers\Member\Shared' => APP_PATH . '/controllers/member/shared',


	/**
	 *	Payment
	 */
	'SakuraPanel\Models\Payment' => APP_PATH . '/models/payment/',
	'SakuraPanel\Controllers\Payment' => APP_PATH . '/controllers/payment/',


	/**
	 *	User
	 */
	'SakuraPanel\Models\User' => APP_PATH . '/models/user/',
	'SakuraPanel\Controllers\User' => APP_PATH . '/controllers/user/',


	/**
	 *	Ajax - Theme - Page
	 */
	'SakuraPanel\Controllers\Ajax' => APP_PATH . '/controllers/ajax/',
	'SakuraPanel\Controllers\Theme' => APP_PATH . '/controllers/theme/',

	
	'SakuraPanel\Controllers\Pages' => APP_PATH . '/controllers/pages/',
	

	/**
	 * Form - Views
	 */
	'SakuraPanel\Forms' => APP_PATH . '/forms/',


))->register();


