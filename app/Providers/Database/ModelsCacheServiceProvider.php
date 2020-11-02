<?php

namespace Sakura\Providers\Database;

use Sakura\Providers\AbstractServiceProvider;

use Phalcon\Cache\Adapter\Stream;
use Phalcon\Storage\SerializerFactory;

/**
 * \Sakura\Providers\ModelsCacheServiceProvider
 *
 * @package Sakura\Providers
 */
class ModelsCacheServiceProvider extends AbstractServiceProvider
{
    /**
     * The Service name.
     * @var string
     */
    protected $serviceName = 'modelsCache';

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
                /** @var \Phalcon\Di $this */
                $config = $this->getConfig();

                            
                $serializerFactory = new SerializerFactory();

                $options = [
                    'lifetime'   => intval( $config->cache->model_life_time ),  
                    'storageDir' => $config->cache->models,
                ];

                return new Stream($serializerFactory, $options);
            }
        );
    }
}
