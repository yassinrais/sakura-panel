<?php
declare(strict_types=1);

namespace Tests\Unit\Library\Plugins;

use Phalcon\Exception as PException;

use Codeception\Test\Unit;
use SakuraPanel\Library\Plugins\Exception;

final class ExceptionTest extends Unit
{
    public function testConstruct(): void
    {
        $class = $this->make(Exception::class);

        $this->assertInstanceOf(PException::class, $class);
    }
}