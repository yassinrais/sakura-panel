<?php

namespace Sakura\Providers\Http;

use Sakura\Providers\AbstractServiceProvider;

use \Sakura\Library\Ajax\AjaxManager;

/**
 * \Sakura\Providers\AjaxServiceProvider
 *
 * @package Sakura\Providers
 */
class AjaxServiceProvider extends AbstractServiceProvider
{
    /**
     * The Service name.
     * @var string
     */
    protected $serviceName = 'ajax';

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
                return new AjaxManager();
            }
        );
    }
}
