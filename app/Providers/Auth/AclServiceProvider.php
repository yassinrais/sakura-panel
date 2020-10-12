<?php

namespace Sakura\Providers\Auth;

use Sakura\Providers\AbstractServiceProvider;

/**
 * \Sakura\Providers\AclServiceProvider
 *
 * @package Sakura\Providers
 */
class AclServiceProvider extends AbstractServiceProvider
{
    /**
     * The Service name.
     * @var string
     */
    protected $serviceName = 'acl';

    /**
     * Register application service.
     *
     * @return void
     */
    public function register()
    {
        $di = $this->di;
        
        $this->di->setShared(
            $this->serviceName,
            function () use ($di) {
                return require BASE_PATH . "/config/acl.php";
            }
        );
    }
}
