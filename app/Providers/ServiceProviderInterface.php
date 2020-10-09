<?php

namespace Sakura\Providers;

use Phalcon\Di\InjectionAwareInterface;

/**
 * \Sakura\Providers\ServiceProviderInterface
 *
 * @package Sakura\Providers
 */
interface ServiceProviderInterface extends InjectionAwareInterface
{
    /**
     * Register application service.
     *
     * @return mixed
     */
    public function register();

    /**
     * Gets the Service name.
     *
     * @return string
     */
    public function getName();
}
