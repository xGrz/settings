<?php

namespace XGrz\Settings\Tests\Feature\Cache;

use Exception;
use XGrz\Settings\Enums\Type;
use XGrz\Settings\Exceptions\SettingKeyNotFoundException;
use XGrz\Settings\Facades\Settings;
use XGrz\Settings\Helpers\Config\SettingsConfig;
use XGrz\Settings\Models\Setting;
use XGrz\Settings\Tests\TestCase;

class CacheTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->artisan('settings:publish-config');
        Settings::invalidateCache();
        $this->artisan('settings:reset', ['--force' => true]);
        Settings::refreshCache();
    }


    public function test_can_cache_settings()
    {
        $this->assertTrue(Setting::count() > 0);
        $this->expectsDatabaseQueryCount(0);
        Settings::all();
        Settings::get('system.');
        settings('system.');
    }

    public function test_refresh_cache_after_setting_created()
    {
        Settings::all();
        Setting::create(['key' => 'new.setting', 'value' => 'testValue', 'type' => Type::STRING]);

        $this->assertDatabaseHas(
            SettingsConfig::getDatabaseTableName(),
            ['key' => 'new.setting', 'value' => 'testValue']
        );

        try {
            $this->assertSame('testValue', settings('new.setting'));
        } catch (Exception $e) {
            $this->fail('Setting key not found in cache');
        }
    }

    public function test_refresh_cache_after_setting_updated()
    {
        $setting = Setting::first();
        $value = rand(3, 3000);
        $setting->update(['value' => $value]);
        $setting->refresh();

        $this->assertDatabaseHas(SettingsConfig::getDatabaseTableName(), ['key' => $setting->key, 'value' => $value]);
        try {
            $this->assertSame($value, Settings::get($setting->key));
        } catch (Exception $e) {
            $this->fail('Setting key not found in cache');
        }
    }

    public function test_refresh_cache_after_setting_deleted()
    {
        $setting = Setting::first();
        $setting->delete();

        $this->assertDatabaseMissing(SettingsConfig::getDatabaseTableName(), ['key' => $setting->key]);
        $this->expectException(SettingKeyNotFoundException::class);

        Settings::get($setting->key);
    }
}