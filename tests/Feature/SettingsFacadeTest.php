<?php

namespace XGrz\Settings\Tests\Feature;

use Illuminate\Support\Facades\Cache;
use XGrz\Settings\Actions\CreateSettingsAction;
use XGrz\Settings\Enums\Type;
use XGrz\Settings\Exceptions\SettingKeyNotFoundException;
use XGrz\Settings\Facades\Settings;
use XGrz\Settings\Models\Setting;
use XGrz\Settings\Tests\TestCase;

class SettingsFacadeTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate');
        $this->artisan('settings:publish-config');
    }

    public function test_can_read_setting_by_key()
    {
        CreateSettingsAction::make()->execute();
        $setting = Setting::inRandomOrder()->firstOrFail();

        $this->assertSame($setting->value, Settings::get($setting->key), 'Can not read setting by key (Facade)');
        $this->assertSame($setting->value, settings($setting->key), 'Can not read setting by key (helper)');
    }

    public function test_facade_get_missing_key_throws_exception()
    {
        CreateSettingsAction::make()->execute();
        $this->expectException(SettingKeyNotFoundException::class);
        $this->expectExceptionMessage('Setting key [missing.key] not found');
        Settings::get('missing.key');
    }

    public function test_setting_helper_get_missing_key_throws_exception()
    {
        CreateSettingsAction::make()->execute();
        $this->expectException(SettingKeyNotFoundException::class);
        $this->expectExceptionMessage('Setting key [missing.key] not found');
        settings('missing.key');
    }

    public function test_setting_helper_get_missing_key_with_default_value_returns_default_value()
    {
        CreateSettingsAction::make()->execute();
        $this->assertSame('default', settings('missing.key', 'default'));
    }

    public function test_setting_can_return_keys_branch()
    {
        CreateSettingsAction::make()->execute();
        $settingsArray = settings('system.seller.');
        $this->assertIsArray($settingsArray);
        $this->assertArrayHasKey('address', $settingsArray);
        $this->assertArrayHasKey('contact', $settingsArray);
    }

    public function test_setting_throws_exception_when_is_not_ending_with_dot()
    {
        $this->expectException(SettingKeyNotFoundException::class);
        CreateSettingsAction::make()->execute();
        settings('system.seller');
    }

    public function test_setting_helper_throws_exception_when_no_key_has_been_provided()
    {
        $this->expectException(SettingKeyNotFoundException::class);
        $this->expectExceptionMessage('Please provide keyName as a parameter');
        settings();
    }

    public function test_settings_facade_return_setting_types_with_translated_labels()
    {
        $types = Settings::getTypes();
        $this->assertIsArray($types);
        $this->assertArrayHasKey(Type::YES_NO->value, $types);
        $this->assertSame(__('settings::types.yes_no'), $types[Type::YES_NO->value]);
    }


    protected function tearDown(): void
    {
        Cache::clear();
        parent::tearDown();
    }
}
