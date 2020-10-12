<?php

namespace Sakura\Providers\View;

use Sakura\Providers\AbstractServiceProvider;

use Phalcon\DiInterface;
use Phalcon\Mvc\View\Engine\Volt;
use Phalcon\Mvc\ViewBaseInterface;

/**
 * \Sakura\Providers\VoltTemplateEngineServiceProvider
 *
 * @package Sakura\Providers
 */
class VoltTemplateEngineServiceProvider extends AbstractServiceProvider
{
    /**
     * The Service name.
     * @var string
     */
    protected $serviceName = 'voltEngine';

    /**
     * Register application service.
     *
     * @return void
     */
    public function register()
    {
        $this->di->setShared(
            $this->serviceName,
            function (ViewBaseInterface $view, $di = null) {
                /** @var \Phalcon\DiInterface $this */
                $config = $this->getShared('config');

                $volt = new Volt($view, $di);

                $volt->setOptions(
                    [
                        'compiledPath'      => $config->cache->views,
                        'compiledSeparator' => $config->compiledSeparator
                    ]
                );

                return $volt;
            }
        );
    }
}
