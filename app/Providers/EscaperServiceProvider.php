<?php

namespace Sakura\Providers;

use Phalcon\Escaper;

/**
 * \Sakura\Providers\EscaperServiceProvider
 *
 * @package Sakura\Providers
 */
class EscaperServiceProvider extends AbstractServiceProvider
{
    /**
     * The Service name.
     * @var string
     */
    protected $serviceName = 'escaper';

    /**
     * Register application service.
     *
     * @return void
     */
    public function register()
    {
        $this->di->setShared($this->serviceName, Escaper::class);
    }
}
