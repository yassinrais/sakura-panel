<?php

namespace Sakura\Providers\Util;

use Sakura\Providers\AbstractServiceProvider;

use Sakura\Library\Translator\Locale;

/**
 * \Sakura\Providers\TranslatorServiceProvider
 *
 * @package Sakura\Services
 */
class TranslatorServiceProvider extends AbstractServiceProvider
{
    /**
     * The Service name.
     * @var string
     */
    protected $serviceName = 'translator';

    /**
     * Register application service.
     *
     * @return void
     */
    public function register()
    {
        $this->di->setShared($this->serviceName, Locale::class);
    }
}
