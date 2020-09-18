<?php
declare(strict_types=1);

namespace Tests\Unit\Controllers;

use Phalcon\Mvc\Controller;

use Codeception\Test\Unit;
use SakuraPanel\Controllers\ControllerBase;

final class ControllerBaseTest extends Unit
{
    public function testConstruct(): void
    {
        $class = $this->make(ControllerBase::class);

        $this->assertInstanceOf(Controller::class, $class);
    }
}