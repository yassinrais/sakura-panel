<?php

namespace Sakura\Providers;

use Phalcon\Events\Manager;

/**
 * \Sakura\Providers\EventManagerServiceProvider
 *
 * @package Sakura\Providers
 */
class EventManagerServiceProvider extends AbstractServiceProvider
{
    /**
     * The Service name.
     * @var string
     */
    protected $serviceName = 'eventsManager';

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
                $em = new Manager();
                $em->enablePriorities(true);

                return $em;
            }
        );
    }
}
