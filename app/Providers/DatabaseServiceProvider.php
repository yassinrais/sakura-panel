<?php

namespace Sakura\Providers;

use Phalcon\Db\Adapter\Pdo\Mysql;

/**
 * \Sakura\Providers\DatabaseServiceProvider
 *
 * @package Sakura\Providers
 */
class DatabaseServiceProvider extends AbstractServiceProvider
{
    /**
     * The Service name.
     * @var string
     */
    protected $serviceName = 'db';

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
                /** @var \Phalcon\DiInterface $this */
                $connection = new Mysql($this->getShared('config')->database->toArray());

                return $connection;
            }
        );
    }
}
