<?php

use Sakura\Providers;

return [
    // Application Managers
    Providers\Manager\EventManagerServiceProvider::class,

    // config
    Providers\Config\ConfigServiceProvider::class,

    // auth
    Providers\Auth\AclServiceProvider::class,
    
    // database
    Providers\Database\DatabaseServiceProvider::class,

    // mvc
    Providers\Mvc\ModelsManagerServiceProvider::class,
    Providers\Mvc\ModelsMetadataServiceProvider::class,
    Providers\Mvc\MvcDispatcherServiceProvider::class,

    // util
    Providers\Util\TagServiceProvider::class,
    Providers\Util\EscaperServiceProvider::class,
    Providers\Util\FlashServiceProvider::class,

    // session
    Providers\Session\SessionServiceProvider::class,
    Providers\Session\CookiesServiceProvider::class,

    // view
    Providers\View\VoltTemplateEngineServiceProvider::class,
    Providers\View\PhpTemplateEngineServiceProvider::class,
    Providers\View\ViewServiceProvider::class,

    // request
    Providers\Http\UrlResolverServiceProvider::class,
    Providers\Http\RouterServiceProvider::class,
    Providers\Http\RequestServiceProvider::class,
    Providers\Http\ResponseServiceProvider::class,

    // Third Party Providers
    // ...
];