<?php

namespace XGrz\Settings\Tests\Feature\ConsoleCommands;

use XGrz\Settings\Enums\Operation;
use XGrz\Settings\Enums\Type;
use XGrz\Settings\Helpers\Config\SettingsConfig;
use XGrz\Settings\Helpers\SettingItems;
use XGrz\Settings\Models\Setting;
use XGrz\Settings\Tests\TestCase;

class SyncCommandTest extends TestCase
{

    protected function setUp(): void
    {
        parent::setUp();
        // $this->resetSettingsConfiguration();

    }

    public function test_sync_with_synchronized_settings_should_return_success()
    {
        $this->artisan('settings:reset', ['--force' => true]);

        $this->artisan('settings:sync')
            ->expectsOutput('Settings are already synchronized')
            ->assertExitCode(0);
    }

    public function test_sync_should_ask_for_confirmation_on_safe_update()
    {
        Setting::truncate();
        $setting = (new SettingItems)->getItems()->random(1)->first();

        $this->artisan('settings:sync')
            ->expectsConfirmation('Do you want to sync settings?', 'yes')
            ->assertExitCode(0);

        $this->assertDatabaseHas(SettingsConfig::getDatabaseTableName(), [
            'key' => $setting->key,
        ]);
    }

    public function test_sync_should_ask_for_confirmation_and_cancel_when_not_confirmed()
    {
        Setting::truncate();
        $this->artisan('settings:sync')
            ->expectsConfirmation('Do you want to sync settings?', 'no')
            ->assertExitCode(0);

        $this->assertDatabaseEmpty(SettingsConfig::getDatabaseTableName());
    }

    public function test_sync_should_render_alert_when_settings_have_forceUpdate_flag_and_update_setting_when_confirmed()
    {
        $this->artisan('settings:reset', ['--force' => true]);
        $testSetting = Setting::where('type', Type::YES_NO)->firstOrFail();
        $testSetting->update(['type' => Type::STRING]);

        $forceItemsNotFound = (new SettingItems())->getItems(Operation::FORCE_UPDATE)->isEmpty();
        $this->assertFalse($forceItemsNotFound, 'TEST CASE FAILED! No force update settings found.');

        $this->artisan('settings:sync')
            ->expectsOutputToContain('WARNING!')
            ->expectsConfirmation('Do you want to force update settings?', 'yes')
            ->assertExitCode(0);

        $this->assertDatabaseHas(SettingsConfig::getDatabaseTableName(), [
            'type' => Type::YES_NO,
            'key' => $testSetting->key,
        ]);
    }

    public function test_synchronize_settings_can_delete_settings_that_are_not_defined_anymore()
    {
        $this->resetSettingsConfiguration();
        $this->artisan('settings:reset', ['--force' => true]);

        $setting = Setting::create(['key' => 'test key', 'type' => Type::STRING, 'value' => 'TestKey']);
        $this->artisan('settings:sync')
            ->expectsConfirmation('Do you want to sync settings?', 'yes')
            ->assertExitCode(0);
        $this->assertDatabaseMissing(SettingsConfig::getDatabaseTableName(), [
            'id' => $setting->id,
        ]);
    }

    public function test_synchronize_settings_can_update_settings_when_definition_has_changed()
    {
        $this->artisan('settings:reset', ['--force' => true]);
        $testSetting = Setting::where('type', Type::YES_NO)->firstOrFail();
        $testSetting->update(['type' => Type::ON_OFF]);

        $this->artisan('settings:sync')
            ->expectsConfirmation('Do you want to sync settings?', 'yes')
            ->assertExitCode(0);

        $this->assertDatabaseHas(SettingsConfig::getDatabaseTableName(), [
            'key' => $testSetting->key,
            'type' => Type::YES_NO,
        ]);
    }

    public function test_sync_command_with_silent_flag_should_return_without_output_when_no_changes_are_made()
    {
        $this->artisan('settings:reset', ['--force' => true]);
        $this->artisan('settings:sync', ['--silent' => true])
            ->assertExitCode(0);
    }

    public function test_sync_command_with_silent_flag_should_return_without_output_when_changes_are_made()
    {
        Setting::truncate();
        $this->artisan('settings:sync', ['--silent' => true])
            ->doesntExpectOutputToContain('Settings')
            ->doesntExpectOutputToContain('WARNING')
            ->doesntExpectOutputToContain('Sync')
            ->doesntExpectOutputToContain(' ')
            ->assertExitCode(0);
    }

    public function test_sync_command_with_silent_and_force_flags_should_return_without_output_when_changes_are_made()
    {
        $this->artisan('settings:reset', ['--force' => true]);
        $setting = Setting::where('type', Type::STRING)->firstOrFail();
        $setting->update(['type' => Type::YES_NO]);

        $this->artisan('settings:sync', ['--force' => true, '--silent' => true])
            ->doesntExpectOutputToContain('Settings')
            ->doesntExpectOutputToContain('WARNING')
            ->doesntExpectOutputToContain('Sync')
            ->doesntExpectOutputToContain(' ')
            ->assertExitCode(0);

        $this->assertDatabaseHas(SettingsConfig::getDatabaseTableName(), [
            'key' => $setting->key,
            'type' => Type::STRING,
        ]);
    }

}