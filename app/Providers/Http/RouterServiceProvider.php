<?php

namespace Sakura\Providers\Http;

use Sakura\Providers\AbstractServiceProvider;

/**
 * \Sakura\Providers\PhpTemplateEngineServiceProvider
 *
 * @package Sakura\Providers
 */
class RouterServiceProvider extends AbstractServiceProvider
{
    /**
     * The Service name.
     * @var string
     */
    protected $serviceName = 'router';

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
                /** @var \Phalcon\DiInterface  $this */
                $appPath = $this->getShared('bootstrap')->getApplicationPath();

                return require $appPath . '/config/routes.php';
            }
        );
    }
}
