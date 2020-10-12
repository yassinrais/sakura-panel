<?php

namespace Sakura\Providers\Mvc;

use Sakura\Providers\AbstractServiceProvider;

use Phalcon\Mvc\Dispatcher;

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
        $namespace = $this->namespace;

        $this->di->setShared(
            $this->serviceName,
            function () use ($namespace) {
                $dispatcher = new Dispatcher();
                $dispatcher->setDefaultNamespace($namespace);

                return $dispatcher;
            }
        );
    }
}
