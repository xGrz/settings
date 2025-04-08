<?php

namespace XGrz\Settings\Tests\Feature\ConsoleCommands;

use XGrz\Settings\Helpers\Config\SettingsConfig;
use XGrz\Settings\Helpers\SettingItems;
use XGrz\Settings\Models\Setting;
use XGrz\Settings\Tests\TestCase;

class ResetCommandTest extends TestCase
{
    public function test_can_force_reset_settings()
    {
        $definitionsCount = (new SettingItems())->getItems()->count();

        $this->assertEquals(0, Setting::count());
        $this->artisan('settings:reset', ['--force' => true]);
        $this->assertDatabaseCount(SettingsConfig::getDatabaseTableName(), $definitionsCount);
    }

    public function test_can_reset_settings()
    {
        $definitionsCount = (new SettingItems())->getItems()->count();

        $this->artisan('settings:reset')
            ->expectsConfirmation('Are you sure you want to reset all settings?', 'yes')
            ->assertExitCode(0);
        $this->assertDatabaseCount(SettingsConfig::getDatabaseTableName(), $definitionsCount);
    }

    public function test_can_abort_resetting_settings()
    {
        $this->expectsDatabaseQueryCount(0);
        $this->artisan('settings:reset')
            ->expectsConfirmation('Are you sure you want to reset all settings?', 'no')
            ->expectsOutput('Aborted. No changes were made.')
            ->assertExitCode(1);
    }
}