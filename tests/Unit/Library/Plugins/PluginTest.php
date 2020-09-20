<?php
declare(strict_types=1);

namespace Tests\Unit\Library\Plugins;


use Codeception\Test\Unit;
use SakuraPanel\Library\Plugins\Plugin;

final class PluginTest extends Unit
{
    public function testConstruct(): void
    {
        $this->assertSame(Plugin::ACTIVE, 1);
        $this->assertSame(Plugin::INACTIVE, 0);
        $this->assertSame(Plugin::DELETED, -1);

        $this->assertSame(Plugin::ROLE_DEFAULT, "guests");

        $this->assertSame(Plugin::PLUGIN_CONFIG_NAME, "plugin.config.php");
        $this->assertSame(Plugin::PLUGIN_CONFIG_JSON, "plugin.config.json");

    }
}