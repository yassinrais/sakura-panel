<?php
declare(strict_types=1);

namespace Tests\Unit\Forms\Users;

use Phalcon\Forms\Form;

use Codeception\Test\Unit;
use Sakura\Forms\Users\UsersForm;

final class UsersFormTest extends Unit
{
    public function testConstruct(): void
    {
        $class = $this->make(UsersForm::class);

        $this->assertInstanceOf(Form::class, $class);
    }
}