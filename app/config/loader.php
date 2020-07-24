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
	'SakuraCore\Library'=>APP_PATH. '/library/',

	/**
	 *	Base Shared
	 */
	'SakuraCore\Models' => APP_PATH . '/models/',
	'SakuraCore\Controllers' => APP_PATH . '/controllers/',

	/**
	 *	App
	 */
	'SakuraCore\Models\App' => APP_PATH . '/models/app/',
	'SakuraCore\Controllers\App' => APP_PATH . '/controllers/app/',

	/**
	 *	Data
	 */
	'SakuraCore\Models\Data' => APP_PATH . '/models/data/',
	'SakuraCore\Controllers\Data' => APP_PATH . '/controllers/data/',

	/**
	 *	Role
	 */
	'SakuraCore\Models\Role' => APP_PATH . '/models/role/',
	'SakuraCore\Controllers\Role' => APP_PATH . '/controllers/role/',

	/**
	 *	Security
	 */
	'SakuraCore\Models\Security' => APP_PATH . '/models/security/',
	'SakuraCore\Controllers\Security' => APP_PATH . '/controllers/security/',

	/**
	 *	Member
	 */
	'SakuraCore\Models\Member' => APP_PATH .'/models/member/',
	'SakuraCore\Controllers\Member' => APP_PATH .'/controllers/member/',
		// :: user profile :: // 
		'SakuraCore\Controllers\Member\Profile' => APP_PATH .'/controllers/member/profile',
		// :: shared controllers :: //
		'SakuraCore\Controllers\Member\Shared' => APP_PATH . '/controllers/member/shared',


	/**
	 *	Payment
	 */
	'SakuraCore\Models\Payment' => APP_PATH . '/models/payment/',
	'SakuraCore\Controllers\Payment' => APP_PATH . '/controllers/payment/',


	/**
	 *	User
	 */
	'SakuraCore\Models\User' => APP_PATH . '/models/user/',
	'SakuraCore\Controllers\User' => APP_PATH . '/controllers/user/',


	/**
	 *	Ajax - Theme - Page
	 */
	'SakuraCore\Controllers\Ajax' => APP_PATH . '/controllers/ajax/',
	'SakuraCore\Controllers\Theme' => APP_PATH . '/controllers/theme/',

	
	'SakuraCore\Controllers\Pages' => APP_PATH . '/controllers/pages/',
	

	/**
	 * Form - Views
	 */
	'SakuraCore\Forms' => APP_PATH . '/forms/',


))->register();


