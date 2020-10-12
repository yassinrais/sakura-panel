<?php

namespace Sakura\Providers\Database;

use Sakura\Providers\AbstractServiceProvider;

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
                /** @var \Phalcon\Di $this */
                $config = $this->getConfig();

                $class = 'Phalcon\Db\Adapter\Pdo\\' . $config->database->adapter;
                $params = [
                    'host'     => $config->database->host,
                    'username' => $config->database->username,
                    'password' => $config->database->password,
                    'dbname'   => $config->database->dbname,
                    'charset'  => $config->database->charset
                ];
            
                if ($config->database->adapter == 'Postgresql') {
                    unset($params['charset']);
                }
                $connection = new \stdClass();
                try{
                    $connection = new $class($params);
                }catch(\Exception $e){
                    $this->getLogger()->error($e->getMessage());
                    
                    die("Database Connection Failed ! Contact Webmaster .");
                }
                return $connection;
            }
        );
    }
}
