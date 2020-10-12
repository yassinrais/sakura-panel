<?php

namespace Sakura\Providers\Session;

use Sakura\Providers\AbstractServiceProvider;

use Phalcon\Http\Response\Cookies;

/**
 * \Sakura\Providers\CookiesServiceProvider
 *
 * @package Sakura\Providers
 */
class CookiesServiceProvider extends AbstractServiceProvider
{
    /**
     * The Service name.
     * @var string
     */
    protected $serviceName = 'cookies';

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
                $cookies = new Cookies();
                $cookies->useEncryption(true);
                return $cookies;
            }
        );
    }
}
