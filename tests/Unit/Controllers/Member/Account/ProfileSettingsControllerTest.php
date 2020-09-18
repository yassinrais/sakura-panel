<?php
declare(strict_types=1);

namespace Tests\Unit\Controllers\Member\Account;



use Phalcon\Mvc\Controller;

use Codeception\Test\Unit;
use SakuraPanel\Controllers\Member\Account\ProfileSettingsController;

final class ProfileSettingsControllerTest extends Unit
{
    public function testConstruct(): void
    {
        $class = $this->make(ProfileSettingsController::class);

        $this->assertInstanceOf(Controller::class, $class);
    }
}