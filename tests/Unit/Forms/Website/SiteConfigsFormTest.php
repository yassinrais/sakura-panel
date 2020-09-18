<?php
declare(strict_types=1);

namespace Tests\Unit\Forms\Website;

use Phalcon\Forms\Form;

use Codeception\Test\Unit;
use SakuraPanel\Forms\Website\SiteConfigsForm;

final class SiteConfigsFormTest extends Unit
{
    public function testConstruct(): void
    {
        $class = $this->make(SiteConfigsForm::class);

        $this->assertInstanceOf(Form::class, $class);
    }
}