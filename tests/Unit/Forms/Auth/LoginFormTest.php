<?php
declare(strict_types=1);

namespace Tests\Unit\Forms\Auth;

use Phalcon\Forms\Form;

use Codeception\Test\Unit;
use Sakura\Forms\Auth\LoginForm;

final class LoginFormTest extends Unit
{
    public function testConstruct(): void
    {
        $class = $this->make(LoginForm::class);

        $this->assertInstanceOf(Form::class, $class);
    }
}