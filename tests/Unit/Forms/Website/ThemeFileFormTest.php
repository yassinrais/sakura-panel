<?php
declare(strict_types=1);

namespace Tests\Unit\Forms\Website;

use Phalcon\Forms\Form;

use Codeception\Test\Unit;
use Sakura\Forms\Website\ThemeFileForm;

final class ThemeFileFormTest extends Unit
{
    public function testConstruct(): void
    {
        $class = $this->make(ThemeFileForm::class);

        $this->assertInstanceOf(Form::class, $class);
    }
}