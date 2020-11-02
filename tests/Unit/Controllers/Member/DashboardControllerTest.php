<?php
declare(strict_types=1);

namespace Tests\Unit\Controllers\Member;



use Phalcon\Mvc\Controller;

use Codeception\Test\Unit;
use Sakura\Controllers\Member\DashboardController;

final class DashboardControllerTest extends Unit
{
    public function testConstruct(): void
    {
        $class = $this->make(DashboardController::class);

        $this->assertInstanceOf(Controller::class, $class);
    }
}