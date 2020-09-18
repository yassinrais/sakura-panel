<?php
declare(strict_types=1);

namespace Tests\Unit\Controllers\Admin\Website;



use Phalcon\Mvc\Controller;

use Codeception\Test\Unit;
use SakuraPanel\Controllers\Admin\Website\ThemeController;

final class ThemeControllerTest extends Unit
{
    public function testConstruct(): void
    {
        $class = $this->make(ThemeController::class);

        $this->assertInstanceOf(Controller::class, $class);
    }
}