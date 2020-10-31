<?php

namespace Sakura\Providers\Manager;

use Sakura\Providers\AbstractServiceProvider;

use Sakura\Library\Widgets\WidgetsLoader;

/**
 * \Sakura\Providers\WidgetsServiceProvider
 *
 * @package Sakura\Providers
 */
class WidgetsServiceProvider extends AbstractServiceProvider
{
    /**
     * The Service name.
     * @var string
     */
    protected $serviceName = 'widgets';

    /**
     * Register application service.
     *
     * @return void
     */
    public function register()
    {
        $this->di->setShared(
            $this->serviceName,
            function () {
                $service =  new WidgetsLoader();
                return $service;
            }
        );
    }
}
