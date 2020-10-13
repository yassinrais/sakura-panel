<?php

use Sakura\Providers;

return [
    // Application Managers
    Providers\Manager\EventManagerServiceProvider::class,
    Providers\Manager\SiteServiceProvider::class,
    Providers\Manager\PageServiceProvider::class,

    // config
    Providers\Config\ConfigServiceProvider::class,
    
    // database
    Providers\Database\DatabaseServiceProvider::class,

    // mvc
    Providers\Mvc\ModelsManagerServiceProvider::class,
    Providers\Mvc\ModelsMetadataServiceProvider::class,
    Providers\Mvc\MvcDispatcherServiceProvider::class,

    // auth
    Providers\Auth\AclServiceProvider::class,

    // util
    Providers\Util\TagServiceProvider::class,
    Providers\Util\TranslatorServiceProvider::class,
    Providers\Util\EscaperServiceProvider::class,
    Providers\Util\FlashServiceProvider::class,
    Providers\Util\LoggerServiceProvider::class,

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
    Providers\Http\AjaxServiceProvider::class,

    // Third Party Providers
    // ...
];