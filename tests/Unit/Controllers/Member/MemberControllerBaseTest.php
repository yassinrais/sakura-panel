<?php
declare(strict_types=1);

namespace Tests\Unit\Controllers\Member;



use Phalcon\Mvc\Controller;

use Codeception\Test\Unit;
use Sakura\Controllers\Member\MemberControllerBase;

final class MemberControllerBaseTest extends Unit
{
    public function testConstruct(): void
    {
        $class = $this->make(MemberControllerBase::class);

        $this->assertInstanceOf(Controller::class, $class);
    }
}