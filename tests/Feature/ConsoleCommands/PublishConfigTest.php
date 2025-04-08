<?php

namespace XGrz\Settings\Tests\Feature\ConsoleCommands;

use Illuminate\Support\Facades\File;
use XGrz\Settings\Helpers\Config\SettingsConfig;
use XGrz\Settings\Tests\TestCase;

class PublishConfigTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        File::delete(SettingsConfig::getConfigPathFile());
        File::delete(SettingsConfig::getDefinitionsPathFile());
    }

    public function test_can_publish_config_file()
    {
        $this->artisan('settings:publish-config')
            ->assertExitCode(0);

        $this->assertFileExists(config_path('app-settings.php'), 'Config file was not published.');
        $this->assertFileEquals(
            __DIR__ . '/../../../config/package-config.php',
            SettingsConfig::getConfigPathFile(),
            'Config file was not published correctly (file content not equal).'
        );
    }

    public function test_can_publish_definitions_file()
    {
        $this->artisan('settings:publish-config')
            ->assertExitCode(0);

        $this->assertFileExists(base_path('settings/definitions.php'), 'Definitions file was not published.');
        $this->assertFileEquals(
            __DIR__ . '/../../../settings/definitions.php',
            SettingsConfig::getDefinitionsPathFile(),
            'Definitions file was not published correctly (file content not equal).'
        );
    }

}