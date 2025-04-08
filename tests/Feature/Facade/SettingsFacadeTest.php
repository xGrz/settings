<?php

namespace XGrz\Settings\Tests\Feature\Facade;

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
        $this->artisan('settings:publish-config');
        $this->artisan('settings:reset', ['--force' => true]);
    }

    public function test_can_read_setting_by_key()
    {
        $setting = Setting::inRandomOrder()->firstOrFail();

        $this->assertSame($setting->value, Settings::get($setting->key), 'Can not read setting by key (Facade)');
        $this->assertSame($setting->value, settings($setting->key), 'Can not read setting by key (helper)');
    }

    public function test_settings_facade_key_not_found_throws_exception()
    {
        $this->expectException(SettingKeyNotFoundException::class);
        $this->expectExceptionMessage('Setting key [missing.key] not found');
        Settings::get('missing.key');
    }

    public function test_settings_helper_key_not_found_throws_exception()
    {
        $this->expectException(SettingKeyNotFoundException::class);
        $this->expectExceptionMessage('Setting key [missing.key] not found');
        settings('missing.key');
    }

    public function test_setting_helper_return_default_value_when_key_not_found()
    {
        $this->assertSame('default', settings('missing.key', 'default'));
    }

    public function test_setting_can_return_array_keys_branch()
    {
        $settingsArray = settings('system.address.');
        $this->assertIsArray($settingsArray);
        $this->assertArrayHasKey('name', $settingsArray);
        $this->assertArrayHasKey('city', $settingsArray);
    }

    public function test_setting_throws_exception_when_is_not_ending_with_dot_on_array_return_expectation()
    {
        $this->expectException(SettingKeyNotFoundException::class);
        settings('system.address');
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


}