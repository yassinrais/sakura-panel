<?php

namespace Sakura\Providers\Mvc;

use Phalcon\Mvc\Dispatcher;

use Sakura\Providers\AbstractServiceProvider;
use Sid\Phalcon\AuthMiddleware\Event;


/**
 * \Sakura\Providers\MvcDispatcherServiceProvider
 *
 * @package Sakura\Providers
 */
class MvcDispatcherServiceProvider extends AbstractServiceProvider
{
    /**
     * The Service name.
     * @var string
     */
    protected $serviceName = 'dispatcher';

    /**
     * This namespace is applied to the controller routes in your routes file.
     * @var string
     */
    protected $namespace = 'Sakura\Controllers';

    /**
     * Register application service.
     *
     * @return void
     */
    public function register()
    {
        $di = $this->di;
        $namespace = $this->namespace;

        $this->di->setShared(
            $this->serviceName,
            function () use ($namespace , $di) {

                $dispatcher = new Dispatcher();

                $eventsManager = $di->getShared('eventsManager');

                // $eventsManager->attach(
                //     "dispatch:beforeException",
                //     function($event, $dispatcher, $exception)
                //     {
                //         if (getenv('APP_DEBUG') != "true") {
                //             switch ($exception->getCode()) {
                //                 case DispatcherException::EXCEPTION_HANDLER_NOT_FOUND:
                //                     if ($this->getRequest()->isAjax()) return $this->getResponse()->send();
                //                     $dispatcher->forward(
                //                         array(
                //                             'controller' => 'Pages\PageErrors',
                //                             'action'     => 'e404',
                //                         )
                //                     );
                //                     return false;
                //                 case DispatcherException::EXCEPTION_ACTION_NOT_FOUND:
                //                     if ($this->getRequest()->isAjax()) return $this->getResponse()->send();
                //                     $dispatcher->forward(
                //                         array(
                //                             'controller' => 'Pages\PageErrors',
                //                             'action'     => 'e404',
                //                         )
                //                     );
                //                     return false;
                //             }
                //         }
                //     }
                // );

                // attach auth event
                $eventsManager->attach(
                    "dispatch:beforeExecuteRoute",
                     new Event()
                );

                $dispatcher->setDefaultNamespace($namespace);
                $dispatcher->setEventsManager($eventsManager);


                return $dispatcher;
            }
        );
    }
}
