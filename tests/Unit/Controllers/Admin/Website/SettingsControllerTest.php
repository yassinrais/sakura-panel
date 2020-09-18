<?php
declare(strict_types=1);

namespace Tests\Unit\Controllers\Admin\Website;



use Phalcon\Mvc\Controller;

use Codeception\Test\Unit;
use SakuraPanel\Controllers\Admin\Website\SettingsController;

final class SettingsControllerTest extends Unit
{
    public function testConstruct(): void
    {
        $class = $this->make(SettingsController::class);

        $this->assertInstanceOf(Controller::class, $class);
    }
}