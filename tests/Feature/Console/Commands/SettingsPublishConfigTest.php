<?php

namespace XGrz\Settings\Tests\Feature\Console\Commands;

use Illuminate\Support\Facades\File;
use XGrz\Settings\Tests\TestCase;

class SettingsPublishConfigTest extends TestCase
{

    protected function setUp(): void
    {
        parent::setUp();
        File::delete(config_path('app-settings.php'));
        File::delete(base_path('settings/definitions.php'));
    }

    public function test_can_publish_config_file()
    {
        $this->artisan('settings:publish-config')
            ->assertExitCode(0);

        $this->assertFileExists(config_path('app-settings.php'), 'Config file was not published.');
        $this->assertFileEquals(
            __DIR__ . '/../../../../config/package-config.php',
            config_path('app-settings.php'), 'Config file was not published correctly (file content not equal).'
        );
    }

    public function test_can_publish_definitions_file()
    {
        $this->artisan('settings:publish-config')
            ->assertExitCode(0);

        $this->assertFileExists(base_path('settings/definitions.php'), 'Definitions file was not published.');
        $this->assertFileEquals(
            __DIR__ . '/../../../../settings/definitions.php',
            base_path('settings/definitions.php'), 'Definitions file was not published correctly (file content not equal).'
        );
    }
}