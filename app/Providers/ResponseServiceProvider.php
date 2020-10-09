<?php

namespace Sakura\Providers;

use Phalcon\Http\Response;

/**
 * \Sakura\Providers\ResponseServiceProvider
 *
 * @package Sakura\Providers
 */
class ResponseServiceProvider extends AbstractServiceProvider
{
    /**
     * The Service name.
     * @var string
     */
    protected $serviceName = 'response';

    /**
     * Register application service.
     *
     * @return void
     */
    public function register()
    {
        $this->di->setShared($this->serviceName, Response::class);
    }
}
