<?php
declare(strict_types=1);

namespace Tests\Unit\Controllers\Admin\Users;



use Phalcon\Mvc\Controller;

use Codeception\Test\Unit;
use Sakura\Controllers\Admin\Users\UsersController;

final class UsersControllerTest extends Unit
{
    public function testConstruct(): void
    {
        $class = $this->make(UsersController::class);

        $this->assertInstanceOf(Controller::class, $class);
    }
}