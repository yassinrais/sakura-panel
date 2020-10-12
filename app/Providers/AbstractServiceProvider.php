<?php

namespace Sakura\Providers;

use Phalcon\Di;
use Phalcon\Di\Injectable;

/**
 * \Sakura\Providers\AbstractServiceProvider
 *
 * @package Sakura\Providers
 */
abstract class AbstractServiceProvider extends Injectable implements ServiceProviderInterface
{
    /**
     * The Service name.
     * @var string
     */
    protected $serviceName;

    /**
     * AbstractServiceProvider constructor.
     *
     * @param DiInterface $di The Dependency Injector.
     */
    public function __construct(Di $di)
    {
        $this->setDI($di);
    }

    /**
     * Gets the Service name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->serviceName;
    }
}
