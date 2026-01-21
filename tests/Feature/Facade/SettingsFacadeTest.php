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

    public function test_settings_facade_return_setting_types_with_translated_labels()
    {
        $types = Settings::getTypes();
        $this->assertIsArray($types);
        $this->assertArrayHasKey(Type::YES_NO->value, $types);
        $this->assertSame(__('settings::types.yes_no'), $types[Type::YES_NO->value]);
    }

    public function test_set_setting_value()
    {
        Settings::set('system.address.name', '2nd Street');
        $this->assertSame('2nd Street', settings('system.address.name'));
    }

    public function test_set_setting_value_with_global_helper()
    {
        setSetting('system.address.name', '3rd Street');
        $this->assertSame('3rd Street', settings('system.address.name'));
    }

    public function test_set_setting_value_for_non_existing_key()
    {
        $this->expectException(SettingKeyNotFoundException::class);
        $this->expectExceptionMessage('Setting key [system.address.nam] not found');

        Settings::set('system.address.nam', '2nd Street');
        $this->assertSame('2nd Street', settings('system.address.name'));
    }

    public function test_can_get_setting_type()
    {
        $type = Settings::type('system.address.name');
        $this->assertSame(Type::STRING, $type);
    }

    public function test_can_get_all_settings_with_get_method()
    {
        $settings = Settings::get();

        $this->assertIsArray($settings);
        $this->assertArrayHasKey('system.address.name', $settings);
        $this->assertEquals(10, count($settings));
    }


}