<?php

namespace Sakura\Providers;

// session
use \Phalcon\Session\Adapter\Stream as SessionAdapter;
use \Phalcon\Session\Manager as SessionManager;

/**
 * \Sakura\Providers\SessionServiceProvider
 *
 * @package Sakura\Providers
 */
class SessionServiceProvider extends AbstractServiceProvider
{
    /**
     * The Service name.
     * @var string
     */
    protected $serviceName = 'session';

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
               
                $session = new SessionManager();
                $files = new SessionAdapter([
                    'savePath' => sys_get_temp_dir(),
                ]);
                $session->setAdapter($files);
                $session->start();
            
                return $session;
            }
        );
    }
}
