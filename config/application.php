<?php


/**
 * vendor include_once 
 */
include_once BASE_PATH . '/vendor/autoload.php';

/**
 * Env Loader
 */
if (is_file(BASE_PATH . '/.env')){
    $dotenv = Dotenv\Dotenv::createImmutable(BASE_PATH);
    $dotenv->load();
}elseif(php_sapi_name() != "cli"){
    exit('Configuration are missing ! ');
}


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
        'controllersDir' => APP_PATH . '/Controllers/',
        'modelsDir'      => APP_PATH . '/Models/',
        'migrationsDir'  => BASE_PATH . '/config/migrations/',
        'pluginsDir'     => APP_PATH . '/Plugins/',
        'libraryDir'     => APP_PATH . '/Library/',
        'formsDir'       => APP_PATH . '/Forms/',
        'translateDir'   => APP_PATH . '/Translations/',
        
        'viewsDir'       => BASE_PATH . '/resources/views/default/',
        'widgetsPath'    => BASE_PATH . '/resources/views/default/widgets/',

        'baseUri'        => '/',
        // server configs  doesnt exist in cli ("request" :@ to ignore undefined vars) 
        'baseURL'        => ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || @$_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://"). @$_SERVER['HTTP_HOST'] . "/",
    ],

    /** 
     * Volt Engine configuration
     */
    'volt'=>[
        'separator'=>'_',
        'compiledPath'=>'_',
    ],
    
    /** 
     * Theme configuration
     */
    'theme'=>[
        'path'=> BASE_PATH . '/public/assets/custom/',
        'uri'=> 'assets/custom/',
    ],



    /** 
     * Logger configuration
     */
    'logger' => [
        'path' => BASE_PATH . '/storage/logs/',
        'filename' => 'application.log',
        'format' => '%date% [%type%] %message%',
        'date' => 'Y-m-d H:i:s',
    ],

    /** 
     * Tables configuration
     */
    'tables'=>[
    	'siteconfigs'=>'site_configs'
    ],

    /** 
     * Security configuration
     */
    'security' => [
        // crypt key 
        'crypt' => getenv('SECURITY_CRYPT_KEY') ?? 'c9$jjSwv8NfHvS8*3c$#ZERyq6wEjyhr',
        // validation
        'mail_activation_token_length' => getenv('SECURITY_MAIL_ACTTOKEN_LENGTH') ?? 15,
        'password_reset_token_lenth' => getenv('SECURITY_RESET_PASSTOKEN_LENGTH') ?? 50,

        // max attemps
        'auth_suspend_max_attemps'=> getenv('SECURITY_AUTH_SUSPEND_MAX_ATTEMPS') ??  2, // max 5 try 
        'auth_banned_max_attemps'=> getenv('SECURITY_AUTH_BANNED_MAX_ATTEMPS') ??  2, // max 5 try 
        'auth_suspend_coef'=> getenv('SECURITY_AUTH_SUSPEND_COEF') ??  2, // tota attemps x 2 

        // delays
        'activation_send_delay'=> getenv('SECURITY_SEND_ACTIVATION_DELAY') ?? 1  * 60  ,// 1 minute
        'auth_fake_delay'=> rand(0, getenv('SECURITY_AUTH_FAKE_DELAY') ?? 6), // 5 seconds

        // password 
        'min_password_length'=> getenv('SECURITY_PASSWORD_MIN_LENGTH') ?? 5  ,// 1 minute
        'max_password_length'=> getenv('SECURITY_PASSWORD_MAX_LENGTH') ?? 60  ,// 1 minute
    ],


    /** 
     * Mail configuration
     */
    'mail' => [
        'fromName' => getenv('MAIL_FROM_NAME') ?? 'Sakura Panel',
        'fromEmail' => getenv('MAIL_FROM_ADDRESS') ?? 'info@sakura.io',
        'mailer'=>getenv('MAIL_MAILER') ?? 'mail',
        'smtp' => [
            'server' => getenv('MAIL_HOST') ?? 'mail.sakura.io',
            'port' => getenv('MAIL_PORT') ?? 465,
            'security' => getenv('MAIL_ENCRYPTION') ?? null,
            'username' => getenv('MAIL_USERNAME') ?? null,
            'password' => getenv('MAIL_PASSWORD') ?? null,
        ],
    ],


    /** 
     * Cache configuration
     */
    'cache'=>[
        'default'=> 	BASE_PATH . '/storage/cache/shared/',
        'views'=>		BASE_PATH . '/storage/cache/views/',
        'sessions'=>	BASE_PATH . '/storage/cache/sessions/',
        'security'=>	BASE_PATH . '/storage/cache/security/',
        'plugins'=>		BASE_PATH . '/storage/cache/plugins/',
        'models'=>		BASE_PATH . '/storage/cache/models/',
    
    
    
        'security_life_time'=> 60 * 60 * 24 * 30 , 
        'model_life_time'=> (int) getenv('CAHCE_MODEL_LIFE_TIME') ?: 6, // default 6 seconds
        'shared_life_time'=> (int) getenv('CAHCE_SHARED_LIFE_TIME') ?: 6, // default 6 seconds
    ],


    'acl'=>[
        'resources'=>[
            [ 'name'    => Sakura\Controllers\Auth\AuthController::class                , 'roles' => 'guests'    , 'access' => ['*'] ],
            [ 'name'    => Sakura\Controllers\Auth\AuthController::class                , 'roles' => 'members|admins'    , 'access' => ['logout'] ],

            [ 'name'    => Sakura\Controllers\Pages\PageErrorsController::class         , 'roles'  => '*'               , 'access' => ['*'] ],
            [ 'name'    => Sakura\Controllers\Member\DashboardController::class         , 'roles' => 'members'   , 'access' => ['*'] ],
            [ 'name'    => Sakura\Controllers\Member\ProfileSettingsController::class   , 'roles' => 'members'   , 'access' => ['*'] ],

            [ 'name'    => Sakura\Controllers\Admin\DashboardController::class          , 'roles' => 'admins'           , 'access' => ['*'] ],
            [ 'name'    => Sakura\Controllers\Admin\WebsiteSettingsController::class    , 'roles' => 'admins'           , 'access' => ['*'] ],
            [ 'name'    => Sakura\Controllers\Admin\WebsiteThemeController::class       , 'roles' => 'admins'           , 'access' => ['*'] ],
            [ 'name'    => Sakura\Controllers\Admin\UsersController::class              , 'roles' => 'admins'           , 'access' => ['*'] ],
            [ 'name'    => Sakura\Controllers\Admin\RolesController::class              , 'roles' => 'admins'           , 'access' => ['*'] ],
            [ 'name'    => Sakura\Controllers\Admin\PermissionsController::class              , 'roles' => 'admins'           , 'access' => ['*'] ],
            [ 'name'    => Sakura\Plugins\Pluginsmanager\Controllers\PluginsController::class , 'roles' => 'admins'           , 'access' => ['*'] ],
        ]
    ]

);

/**
 *      Menu
 */
include_once ('inc/menu.inc.php');

// return configs
return new \Phalcon\Config($configs);
