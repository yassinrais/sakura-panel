<?php
declare(strict_types=1);

namespace Tests\Unit\Library\Ajax;

use Phalcon\Mvc\Controller;

use Codeception\Test\Unit;
use Sakura\Library\Ajax\AjaxManager;

final class AjaxManagerTest extends Unit
{
    public function testConstruct(): void
    {
        $class = $this->make(AjaxManager::class);

        $this->assertInstanceOf(Controller::class, $class);
    }
}