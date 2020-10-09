<?php
declare(strict_types=1);

namespace Tests\Unit\Forms;

use Phalcon\Forms\Form;

use Codeception\Test\Unit;
use Sakura\Forms\BaseForm;

final class BaseFormTest extends Unit
{
    public function testConstruct(): void
    {
        $class = $this->make(BaseForm::class);

        $this->assertInstanceOf(Form::class, $class);
    }
}