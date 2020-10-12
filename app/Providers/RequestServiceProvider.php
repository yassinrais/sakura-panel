<?php

namespace Sakura\Providers;

use Phalcon\Http\Request;

/**
 * \Sakura\Providers\RequestServiceProvider
 *
 * @package Sakura\Providers
 */
class RequestServiceProvider extends AbstractServiceProvider
{
    /**
     * The Service name.
     * @var string
     */
    protected $serviceName = 'request';

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
                return new Request();
            }
        );
    }
}
