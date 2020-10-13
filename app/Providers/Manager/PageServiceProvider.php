<?php

namespace Sakura\Providers\Manager;

use Sakura\Providers\AbstractServiceProvider;

use \Sakura\Library\PageInfoManager;

/**
 * \Sakura\Providers\PageServiceProvider
 *
 * @package Sakura\Providers
 */
class PageServiceProvider extends AbstractServiceProvider
{
    /**
     * The Service name.
     * @var string
     */
    protected $serviceName = 'page';

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
                $page =  new PageInfoManager();
                return $page;
            }
        );
    }
}
