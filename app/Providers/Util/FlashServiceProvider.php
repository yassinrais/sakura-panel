<?php

namespace Sakura\Providers\Util;

use Sakura\Providers\AbstractServiceProvider;

use \Phalcon\Escaper;
use \Phalcon\Flash\Direct as Flash;
use \Phalcon\Flash\Session as FlashSession;

/**
 * \Sakura\Providers\FlashServiceProvider
 *
 * @package Sakura\Providers
 */
class FlashServiceProvider extends AbstractServiceProvider
{
    /**
     * The Service name.
     * @var string
     */
    protected $serviceName = 'flash';

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
        $this->di->setShared($this->serviceName, function () {
            $escaper = new Escaper();
            $flash = new Flash($escaper);
            $flash->setAutoescape(false);
            $flash->setCssClasses([
                'error'   => 'alert alert-danger',
                'success' => 'alert alert-success',
                'notice'  => 'alert alert-info',
                'warning' => 'alert alert-warning'
            ]);

            return $flash;
        });

        /**
         * FlashSession 
         */
        $this->di->setShared("{$this->serviceName}Session", function () {
            $escaper = new Escaper();
            $flash = new FlashSession($escaper);
            $flash->setAutoescape(false);
            $flash->setCssClasses([
                'error'   => 'alert alert-danger',
                'success' => 'alert alert-success',
                'notice'  => 'alert alert-info',
                'warning' => 'alert alert-warning'
            ]);

            return $flash;
        });
    }
}
