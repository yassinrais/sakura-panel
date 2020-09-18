<?php
declare(strict_types=1);

namespace Tests\Unit\Controllers\Pages;

use Phalcon\Mvc\Controller;

use Codeception\Test\Unit;
use SakuraPanel\Controllers\Pages\PageErrorsController;

final class PageErrorsControllerTest extends Unit
{
    public function testConstruct(): void
    {
        $class = $this->make(PageErrorsController::class);

        $this->assertInstanceOf(Controller::class, $class);
    }
}