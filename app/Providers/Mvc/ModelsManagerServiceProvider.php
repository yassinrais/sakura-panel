<?php

namespace Sakura\Providers\Mvc;

use Sakura\Providers\AbstractServiceProvider;

use Phalcon\Mvc\Model\Manager as ModelsManager;

/**
 * \Sakura\Providers\ModelsManagerServiceProvider
 *
 * @package Sakura\Providers
 */
class ModelsManagerServiceProvider extends AbstractServiceProvider
{
    /**
     * The Service name.
     * @var string
     */
    protected $serviceName = 'modelsManager';

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
                $srv = new ModelsManager();

                return $srv;
            }
        );
    }
}
