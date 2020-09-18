<?php
declare(strict_types=1);

namespace Tests\Unit\Controllers\Admin\Users;



use Phalcon\Mvc\Controller;

use Codeception\Test\Unit;
use SakuraPanel\Controllers\Admin\Users\RolesController;

final class RolesControllerTest extends Unit
{
    public function testConstruct(): void
    {
        $class = $this->make(RolesController::class);

        $this->assertInstanceOf(Controller::class, $class);
    }
}