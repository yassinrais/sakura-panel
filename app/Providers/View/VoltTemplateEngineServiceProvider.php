<?php

namespace Sakura\Providers\View;

use Sakura\Providers\AbstractServiceProvider;

use Phalcon\DiInterface;
use Phalcon\Mvc\View\Engine\Volt;
use Phalcon\Mvc\ViewBaseInterface;

/**
 * \Sakura\Providers\VoltTemplateEngineServiceProvider
 *
 * @package Sakura\Providers
 */
class VoltTemplateEngineServiceProvider extends AbstractServiceProvider
{
    /**
     * The Service name.
     * @var string
     */
    protected $serviceName = 'voltEngine';

    /**
     * Register application service.
     *
     * @return void
     */
    public function register()
    {
        $this->di->setShared(
            $this->serviceName,
            function (ViewBaseInterface $view, $di = null) {
                /** @var \Phalcon\DiInterface $this */
                $config = $this->getShared('config');

                $volt = new Volt($view, $di);

                $volt->setOptions(
                    [
                        'path'      => $config->cache->views,
                        'separator' => $config->volt->separator
                    ]
                );
    
                $c = $volt->getCompiler();
                $c->addFunction('substr',  function($resolvedArgs, $exprArgs) use ($c) {
    
                    $string= $c->expression($exprArgs[0]['expr']);
    
                    return 'substr(' . $string . ' , 0 ,' . $c->expression($exprArgs[1]['expr']) . ')';
                });
    
                $c->addFunction('number_format', function ($resolvedArgs, $exprArgs) use ($c) {
                    $firstArgument = $c->expression($exprArgs[0]['expr']);
                    return 'number_format(floatval('.$firstArgument."), 2, '.', ' ')";
                });
    
                $c->addFunction('str_replace','str_replace');
                $c->addFunction('getenv','getenv');
                $c->addFunction('var_dump','var_dump');
                $c->addFunction('ucfirst','ucfirst');
                $c->addFunction('class_exists','class_exists');
                $c->addFunction('explode','explode');
                $c->addFunction('_', function ($resolvedArgs, $exprArgs) {
                    return  $this->getShared('translator')->_($resolvedArgs);
                });
                
                return $volt;
            }
        );
    }
}
