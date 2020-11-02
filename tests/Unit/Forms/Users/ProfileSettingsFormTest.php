<?php
declare(strict_types=1);

namespace Tests\Unit\Forms\Users;

use Phalcon\Forms\Form;

use Codeception\Test\Unit;
use Sakura\Forms\Users\ProfileSettingsForm;

final class ProfileSettingsFormTest extends Unit
{
    public function testConstruct(): void
    {
        $class = $this->make(ProfileSettingsForm::class);

        $this->assertInstanceOf(Form::class, $class);
    }
}