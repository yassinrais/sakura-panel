<?php
declare(strict_types=1);

namespace Tests\Unit\Forms\Users;

use Phalcon\Forms\Form;

use Codeception\Test\Unit;
use Sakura\Forms\Users\RolesForm;

final class RolesFormTest extends Unit
{
    public function testConstruct(): void
    {
        $class = $this->make(RolesForm::class);

        $this->assertInstanceOf(Form::class, $class);
    }
}