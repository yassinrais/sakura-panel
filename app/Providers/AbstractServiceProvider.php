<?php

namespace SakuraPanel\Providers;

use Phalcon\DiInterface;
use Phalcon\Di\Injectable;

/**
 * \SakuraPanel\Providers\AbstractServiceProvider
 *
 * @package SakuraPanel\Providers
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
    public function __construct(DiInterface $di)
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
