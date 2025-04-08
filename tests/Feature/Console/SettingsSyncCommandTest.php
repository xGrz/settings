<?php

namespace XGrz\Settings\Tests\Feature\Console;

use XGrz\Settings\Helpers\SettingsConfig;
use XGrz\Settings\Models\Setting;
use XGrz\Settings\Tests\TestCase;

class SettingsSyncCommandTest extends TestCase
{
    public function test_ask_for_sync_confirmation_is_rendered_and_can_be_aborted()
    {
        $this->artisan('settings:sync')
            ->expectsConfirmation('Do you want to sync settings?', 'no')
            ->expectsOutput('Aborted. No changes were made.')
            ->assertExitCode(2);
    }

    public function test_ask_for_sync_confirmation_is_rendered_and_can_be_confirmed()
    {
        $this->assertDatabaseEmpty(SettingsConfig::getDatabaseTableName());
        $this->artisan('settings:sync')
            ->expectsConfirmation('Do you want to sync settings?', 'yes')
            ->assertExitCode(0);

        $settingsCount = Setting::count();
        $this->assertGreaterThan(0, $settingsCount);
    }

    public function test_settings_are_synced_returns_message_and_exit()
    {
        $this->artisan('settings:sync')
            ->expectsConfirmation('Do you want to sync settings?', 'yes')
            ->assertExitCode(0);

        $this->artisan('settings:sync')
            ->expectsOutput('All settings are synchronized.')
            ->assertExitCode(1);
    }

    public function test_synchronize_settings_can_delete_settings_that_are_not_defined()
    {
    }

    public function test_synchronize_settings_can_create_settings_that_are_not_defined()
    {
    }

    public function test_synchronize_settings_can_update_settings_that_are_not_defined()
    {
    }

    public function test_synchronize_settings_is_not_updating_settings_with_force_flag()
    {
    }
}