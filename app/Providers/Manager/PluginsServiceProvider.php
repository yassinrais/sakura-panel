<?php

namespace Sakura\Providers\Manager;

use Sakura\Providers\AbstractServiceProvider;

use Sakura\Library\Plugins\PluginsManager;

/**
 * \Sakura\Providers\PluginsServiceProvider
 *
 * @package Sakura\Providers
 */
class PluginsServiceProvider extends AbstractServiceProvider
{
    /**
     * The Service name.
     * @var string
     */
    protected $serviceName = 'plugins';

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
                $plugins =  new PluginsManager();
                return $plugins;
            }
        );
    }
}
