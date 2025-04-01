<?php


use XGrz\Settings\Enums\KeyNaming;
use XGrz\Settings\Enums\SettingType;
use XGrz\Settings\Helpers\SettingsConfig;
use XGrz\Settings\Models\Setting;
use XGrz\Settings\Tests\TestCase;

class ArtisanCommandsTest extends TestCase
{

    public function test_artisan_init_command(): void
    {
        Setting::truncate();

        $this
            ->artisan('settings:init')
            ->assertExitCode(0);

        $this->assertDatabaseCount(SettingsConfig::getDatabaseTableName(), 9);
    }

    public function test_artisan_publish_settings_config_command(): void
    {
        $this
            ->artisan('settings:publish-config')
            ->assertExitCode(0);

    }

    public function test_artisan_settings_are_published()
    {
        File::delete(config_path('app-settings.php'));
        $this->artisan('settings:publish-config');

        $this->assertFileExists(config_path('app-settings.php'));
        $this->assertFileEquals(__DIR__ . '/../../config/package-config.php', config_path('app-settings.php'));


        // clean-up
        File::delete(config_path('app-settings.php'));
    }

    public function test_artisan_publish_lang_command(): void
    {
        $this
            ->artisan('settings:publish-lang')
            ->assertExitCode(0);

    }

    public function test_artisan_update_keys_command_abort()
    {
        $this->artisan('settings:update-keys')
            ->expectsConfirmation('Are you sure you want to update keys?', 'no')
            ->assertExitCode(254);
    }

    public function test_artisan_update_keys_command_confirmed()
    {
        \Illuminate\Support\Facades\Config::set('app-settings.key_name_generator', KeyNaming::CAMEL_CASE);
        $testSetting = Setting::create(['prefix' => 'systemSettings', 'suffix' => 'suffixName', 'setting_type' => SettingType::TEXT]);

        \Illuminate\Support\Facades\Config::set('app-settings.key_name_generator', KeyNaming::KEBAB_CASE);
        $this->artisan('settings:update-keys')
            ->expectsConfirmation('Are you sure you want to update keys?', 'yes')
            ->assertExitCode(0);

        $this->assertDatabaseHas(SettingsConfig::getDatabaseTableName(), [
            'key' => 'system-settings.suffix-name',
        ]);
        $this->assertDatabaseMissing(SettingsConfig::getDatabaseTableName(), [
            'key' => 'systemSettings.suffixName'
        ]);

    }
}
