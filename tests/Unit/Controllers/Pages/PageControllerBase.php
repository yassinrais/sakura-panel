<?php
declare(strict_types=1);

namespace Tests\Unit\Controllers\Pages;

use Phalcon\Mvc\Controller;

use Codeception\Test\Unit;
use Sakura\Controllers\Pages\PageControllerPage;

final class PageControllerBaseTest extends Unit
{
    public function testConstruct(): void
    {
        $class = $this->make(PageControllerPage::class);

        $this->assertInstanceOf(Controller::class, $class);
    }
}