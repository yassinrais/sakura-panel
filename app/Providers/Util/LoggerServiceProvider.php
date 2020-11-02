<?php

namespace Sakura\Providers\Util;

use Sakura\Providers\AbstractServiceProvider;

use \Phalcon\Logger;
use \Phalcon\Logger\Formatter\Line as LineFormatter;
use \Phalcon\Logger\Adapter\Stream as StreamLogger;


/**
 * \Sakura\Providers\LoggerServiceProvider
 *
 * @package Sakura\Providers
 */
class LoggerServiceProvider extends AbstractServiceProvider
{
    /**
     * The Service name.
     * @var string
     */
    protected $serviceName = 'logger';

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
        $di = $this->di;

        $this->di->setShared($this->serviceName, function ($filename = null, $format = null) use ($di) {
            $loggerConfigs = $di->getShared('config')->get('logger');
        
            $format = $format ?: $loggerConfigs->format;
            $filename = trim(date('Y-m-d.').$loggerConfigs->get('filename'), '\\/');
            $path = rtrim($loggerConfigs->get('path'), '\\/') . DIRECTORY_SEPARATOR;
        
            $formatter = new LineFormatter($format, $loggerConfigs->date);
            $adapter = new StreamLogger($path . $filename);
            $adapter->setFormatter($formatter);
        
            $logger = new Logger(
                'messages',
                [
                    'main' => $adapter,
                ]
            );
        
            return $logger;
        });
    }
}
