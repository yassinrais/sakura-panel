<?php

namespace Sakura\Providers\Manager;

use Sakura\Providers\AbstractServiceProvider;

use \Sakura\Library\SiteManager;

/**
 * \Sakura\Providers\SiteServiceProvider
 *
 * @package Sakura\Providers
 */
class SiteServiceProvider extends AbstractServiceProvider
{
    /**
     * The Service name.
     * @var string
     */
    protected $serviceName = 'site';

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
                $site =  new SiteManager();
                $site->initialize();
                return $site;
            }
        );
    }
}
