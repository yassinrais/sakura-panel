<?php

namespace Sakura\Providers\Util;

use Sakura\Providers\AbstractServiceProvider;

use Phalcon\Cache\Adapter\Stream;
use Phalcon\Storage\SerializerFactory;

/**
 * \Sakura\Providers\CacheServiceProvider
 *
 * @package Sakura\Providers
 */
class CacheServiceProvider extends AbstractServiceProvider
{
    /**
     * The Service name.
     * @var string
     */
    protected $serviceName = 'cache';

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
                    'lifetime'   => intval( $config->cache->shared_life_time ),  
                    'storageDir' => $config->cache->default,
                ];

                return new Stream($serializerFactory, $options);
            }
        );
    }
}
