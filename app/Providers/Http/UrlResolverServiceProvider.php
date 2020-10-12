<?php

namespace Sakura\Providers\Http;

use Sakura\Providers\AbstractServiceProvider;


use \Phalcon\Url;

/**
 * \Sakura\Providers\UrlResolverServiceProvider
 *
 * @package Sakura\Providers
 */
class UrlResolverServiceProvider extends AbstractServiceProvider
{
    /**
     * The Service name.
     * @var string
     */
    protected $serviceName = 'url';

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
                $url = new Url();

                /** @var \Phalcon\DiInterface $this */
                $url->setBaseUri($this->getShared('config')->application->baseUri);

                return $url;
            }
        );
    }
}
