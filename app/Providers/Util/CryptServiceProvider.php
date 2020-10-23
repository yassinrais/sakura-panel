<?php

namespace Sakura\Providers\Util;

use Sakura\Providers\AbstractServiceProvider;

use Phalcon\Crypt;

/**
 * \Sakura\Providers\CryptServiceProvider
 *
 * @package Sakura\Providers
 */
class CryptServiceProvider extends AbstractServiceProvider
{
    /**
     * The Service name.
     * @var string
     */
    protected $serviceName = 'crypt';

    /**
     * Register application service.
     *
     * @return void
     */
    public function register()
    {
        /**
         * Register the session flash service with the Twitter Bootstrap classes
         */
        $config = $this->di->getConfig();
        $this->di->setShared($this->serviceName, function () {
            $crypt = new Crypt();
            $crypt->setKey($config->security->crypt ?? "EMPTY_KEY");
            return $crypt;
        });
 
    }
}
