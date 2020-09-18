<?php
declare(strict_types=1);

namespace Tests\Unit\Controllers\Pages;

use Phalcon\Mvc\Controller;

use Codeception\Test\Unit;
use SakuraPanel\Controllers\Pages\HomeController;

final class HomeControllerTest extends Unit
{
    public function testConstruct(): void
    {
        $class = $this->make(HomeController::class);

        $this->assertInstanceOf(Controller::class, $class);
    }
}