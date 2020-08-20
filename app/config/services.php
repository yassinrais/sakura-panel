<?php
declare(strict_types=1);

// flash messages
use \Phalcon\Escaper;
use \Phalcon\Flash\Direct as Flash;
use \Phalcon\Flash\Session as FlashSession;

// Mvc View
use \Phalcon\Mvc\Model\Metadata\Memory as MetaDataAdapter;
use \Phalcon\Mvc\View;
use \Phalcon\Mvc\View\Engine\Php as PhpEngine;
use \Phalcon\Mvc\View\Engine\Volt as VoltEngine;

// url
use \Phalcon\Url as UrlResolver;

// session
use \Phalcon\Session\Adapter\Stream as SessionAdapter;
use \Phalcon\Session\Manager as SessionManager;

// logger
use \Phalcon\Logger;
use \Phalcon\Logger\Formatter\Line as LineFormatter;
use \Phalcon\Logger\Adapter\Stream as StreamLogger;

// mail
use SakuraPanel\Library\Mail\Mail;

// cache
use Phalcon\Cache\Adapter\Stream;
use Phalcon\Storage\SerializerFactory;

// exception
use Phalcon\Dispatcher\Exception as DispatcherException;




/**
 * Shared configuration service
 */
$di->setShared('config', function () {

    return include APP_PATH . "/config/config.php";
});

/**
 * The URL component is used to generate all kind of urls in the application
 */
$di->setShared('url', function () {
    $config = $this->getConfig();

    $url = new UrlResolver();
    $url->setBaseUri($config->application->baseUri);

    return $url;
});

/**
 * Database connection is created based in the parameters defined in the configuration file
 */
$di->setShared('db', function () {
    $config = $this->getConfig();

    $class = 'Phalcon\Db\Adapter\Pdo\\' . $config->database->adapter;
    $params = [
        'host'     => $config->database->host,
        'username' => $config->database->username,
        'password' => $config->database->password,
        'dbname'   => $config->database->dbname,
        'charset'  => $config->database->charset
    ];

    if ($config->database->adapter == 'Postgresql') {
        unset($params['charset']);
    }
    $connection = new \stdClass();
    try{
        $connection = new $class($params);
    }catch(\Exception $e){
        $this->getLogger()->error($e->getMessage());
        
        die("Database Connection Failed ! Contact Webmaster .");
    }
    return $connection;
});



/**
 * Setting up the view component
 */
$di->setShared('view', function () {
    $config = $this->getConfig();

    $view = new View();
    $view->setDI($this);

    // set views source dir
    $view->setViewsDir(
        $config->application->viewsDir
    );

    // set engine 
    $view->registerEngines([
        '.volt' => function ($view) {
            $config = $this->getConfig();

            $volt = new VoltEngine($view, $this);

            $volt->setOptions([
                'path' => $config->application->cacheViewsDir,
                'separator' => '_'
            ]);

            $c = $volt->getCompiler();
            $c->addFunction('substr',  function($resolvedArgs, $exprArgs) use ($c) {

                $string= $c->expression($exprArgs[0]['expr']);

                return 'substr(' . $string . ' , 0 ,' . $c->expression($exprArgs[1]['expr']) . ')';
            });

            $c->addFunction('number_format', function ($resolvedArgs, $exprArgs) use ($c) {
                $firstArgument = $c->expression($exprArgs[0]['expr']);
                return 'number_format(floatval('.$firstArgument."), 2, '.', ' ')";
            });

            $c->addFunction('str_replace','str_replace');

            $c->addFunction('getenv','getenv');
            
            $c->addFunction('var_dump','var_dump');
            
            $c->addFunction('class_exists','class_exists');
            
            return $volt;
        },
        '.phtml' => PhpEngine::class

    ]);
    return $view;
});


/**
 * If the configuration specify the use of metadata adapter use it or use memory otherwise
 */
$di->setShared('modelsMetadata', function () {
    return new MetaDataAdapter();
});

/**
 * Register the session flash service with the Twitter Bootstrap classes
 */
$di->setShared('flash', function () {
    $escaper = new Escaper();
    $flash = new Flash($escaper);
    $flash->setAutoescape(false);
    $flash->setCssClasses([
        'error'   => 'alert alert-danger',
        'success' => 'alert alert-success',
        'notice'  => 'alert alert-info',
        'warning' => 'alert alert-warning'
    ]);

    return $flash;
});

/**
 * FlashSession 
 */
$di->setShared('flashSession', function () {
    $escaper = new Escaper();
    $flash = new FlashSession($escaper);
    $flash->setAutoescape(false);
    $flash->setCssClasses([
        'error'   => 'alert alert-danger',
        'success' => 'alert alert-success',
        'notice'  => 'alert alert-info',
        'warning' => 'alert alert-warning'
    ]);

    return $flash;
});

/**
 * Start the session the first time some component request the session service
 */
$di->setShared('session', function () {
    $session = new SessionManager();
    $files = new SessionAdapter([
        'savePath' => sys_get_temp_dir(),
    ]);
    $session->setAdapter($files);
    $session->start();

    return $session;
});

/**
 * Security 
 * crypt
 */
$di->setShared('crypt', function() use($di) {
    $crypt = new \Phalcon\Crypt();
    $crypt->setKey('ReallyRandomKey');
    return $crypt;
});

/**
 * cookies settings
 */
$di->setShared('cookies', function() {
    $cookies = new Phalcon\Http\Response\Cookies();
    $cookies->useEncryption(true);
    return $cookies;
});

 
  

/**
 * Logger service
 */
$di->setShared('logger', function ($filename = null, $format = null) {
    $loggerConfigs = $this->getShared('config')->get('logger');

    $format = $format ?: $loggerConfigs->format;
    $filename = trim(date('Y-m-d.').$loggerConfigs->get('filename'), '\\/');
    $path = rtrim($loggerConfigs->get('path'), '\\/') . DIRECTORY_SEPARATOR;

    $formatter = new LineFormatter($format, $loggerConfigs->date);
    $adapter = new StreamLogger($path . $filename);
    $adapter->setFormatter($formatter);

    $logger = new Logger(
        'messages',
        [
            'main' => $adapter,
        ]
    );

    return $logger;
});

 

/**
 * Cache Security
 */

$di->setShared('securityCache' , function () use ($di)
{
    $config = $this->getConfig();
    
    $serializerFactory = new SerializerFactory();

    $options = [
        'lifetime'          => intval( $config->cache->security_life_time ) ,
        'storageDir' => $config->application->securityCacheDir ?? BASE_PATH . '/cache/security/',
    ];

    return new Stream($serializerFactory, $options);

});

/**
 * global Cache (models)
 */

$di->setShared('modelsCache' , function () use ($di)
{
    $config = $this->getConfig();
    
    $serializerFactory = new SerializerFactory();

    $options = [
        'lifetime'          => intval( $config->cache->model_life_time ),  
        'storageDir' => $config->application->globalCacheDir ?? BASE_PATH . '/cache/global/',
    ];

    return new Stream($serializerFactory, $options);
});

/**
 * shared Cache
 */
$di->setShared('cache' , function () use ($di)
{
    $config = $this->getConfig();
    
    $serializerFactory = new SerializerFactory();

    $options = [
        'lifetime'          => intval( $config->cache->shared_life_time ),   
        'storageDir' => $config->application->cacheDir ?? BASE_PATH . '/cache/shared/',
    ];

    return new Stream($serializerFactory, $options);
});


/**
 * acl service
 */
$di->setShared('acl', function () use($di) {
    return include APP_PATH . "/config/acl.php";
});


/**
 * Dispatcher : event manager
 */
$di->set(
    'dispatcher',
    function() use ($di) {

        $eventsManager = $di->getShared('eventsManager');

        $eventsManager->attach(
            "dispatch:beforeException",
            function($event, $dispatcher, $exception)
            {
                 if (getenv('APP_DEBUG') !== true) {
                    switch ($exception->getCode()) {
                        case DispatcherException::EXCEPTION_HANDLER_NOT_FOUND:
                            if ($this->getRequest()->isAjax()) return $this->getResponse()->send();
                            $dispatcher->forward(
                                array(
                                    'controller' => '\SakuraPanel\Controllers\Pages\PageErrors',
                                    'action'     => 'Page404',
                                )
                            );
                            return false;
                        case DispatcherException::EXCEPTION_ACTION_NOT_FOUND:
                            if ($this->getRequest()->isAjax()) return $this->getResponse()->send();
                            $dispatcher->forward(
                                array(
                                    'controller' => '\SakuraPanel\Controllers\Pages\PageErrors',
                                    'action'     => 'Page404',
                                )
                            );
                            return false;
                    }
                }
            }
        );

        // attach auth event
        $eventsManager->attach(
            "dispatch:beforeExecuteRoute",
            new \Sid\Phalcon\AuthMiddleware\Event()
        );

        // set event manager 
        $dispatcher = new \Phalcon\Mvc\Dispatcher();
        $dispatcher->setEventsManager($eventsManager);

        return $dispatcher;
    },
    true
);



/**
 * Mail service
 */
$di->setShared('mail', function () {

    return new Mail(); 
});


/**
 * Set Website Config : name , title , etc , set controller
 */
$di->setShared('site', function () {
    $site =  new \SakuraPanel\Library\SiteManager();
    $site->initialize();
    return $site;
});

/**
 * Page Config : name , title , etc , set controller
 */
$di->setShared('page', function () {
    return new \SakuraPanel\Library\PageInfoManager();
});


/**
 * Page Config : name , title , etc , set controller
 */
$di->setShared('plugins', function () {
    return new \SakuraPanel\Library\Plugins\PluginsManager();
});


/**
 * Page Config : name , title , etc , set controller
 */
$di->setShared('ajax', function () {
    return new \SakuraPanel\Library\Ajax\AjaxManager();
});


/**
 * @TODO : Delete / Replace  
 * Widgets test : deleted soon !
 */
$di->set(
    'widgets',
    function () use ($di)
    {
        $instance = new \SakuraPanel\Library\Widgets\WidgetsLoader();
        $instance->setWidgetsPath($this->getConfig()->application->widgetsPath);
        $instance->loadWidgets();
        return $instance;
    }
);

