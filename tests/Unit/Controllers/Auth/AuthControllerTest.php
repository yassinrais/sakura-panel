<?php
declare(strict_types=1);

namespace Tests\Unit\Controllers\Auth\Website;



use Phalcon\Mvc\Controller;

use Codeception\Test\Unit;
use Sakura\Controllers\Auth\AuthController;

final class AuthControllerTest extends Unit
{
    public function testConstruct(): void
    {
        $class = $this->make(AuthController::class);

        $this->assertInstanceOf(Controller::class, $class);
    }
}