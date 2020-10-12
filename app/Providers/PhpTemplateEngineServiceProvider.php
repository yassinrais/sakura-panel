<?php

namespace Sakura\Providers;

use Phalcon\Di;
use Phalcon\Mvc\ViewBaseInterface;
use Phalcon\Mvc\View\Engine\Php as PhpEngine;

/**
 * \Sakura\Providers\PhpTemplateEngineServiceProvider
 *
 * @package Sakura\Providers
 */
class PhpTemplateEngineServiceProvider extends AbstractServiceProvider
{
    /**
     * The Service name.
     * @var string
     */
    protected $serviceName = 'phpEngine';

    /**
     * Register application service.
     *
     * @return void
     */
    public function register()
    {
        $this->di->setShared(
            $this->serviceName,
            function (ViewBaseInterface $view,Di $di = null) {
                $engine = new PhpEngine($view, $di);

                return $engine;
            }
        );
    }
}
