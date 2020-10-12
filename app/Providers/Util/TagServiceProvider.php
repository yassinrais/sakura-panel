<?php

namespace Sakura\Providers\Util;

use Sakura\Providers\AbstractServiceProvider;

use Phalcon\Tag;

/**
 * \Sakura\Providers\TagServiceProvider
 *
 * @package Sakura\Services
 */
class TagServiceProvider extends AbstractServiceProvider
{
    /**
     * The Service name.
     * @var string
     */
    protected $serviceName = 'tag';

    /**
     * Register application service.
     *
     * @return void
     */
    public function register()
    {
        $this->di->setShared($this->serviceName, Tag::class);
    }
}
