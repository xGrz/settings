<?php


use XGrz\Settings\Exceptions\SettingKeyNotFoundException;
use XGrz\Settings\Facades\Settings;
use XGrz\Settings\Helpers\InitBaseSettings;
use XGrz\Settings\Helpers\SettingsConfig;
use XGrz\Settings\Models\Setting;
use XGrz\Settings\Tests\TestCase;

class CacheSettingsTest extends TestCase
{

    protected function setUp(): void
    {
        parent::setUp();
        InitBaseSettings::make();
    }

    public function test_can_cache_settings()
    {
        Settings::refreshCache();

        $this->assertTrue(cache()->has(SettingsConfig::getCacheKey()));
    }

    public function test_invalidate_settings_cache()
    {
        Settings::refreshCache();
        $this->assertTrue(cache()->has(SettingsConfig::getCacheKey()));

        Settings::invalidateCache();
        $this->assertFalse(cache()->has(SettingsConfig::getCacheKey()));
    }

    public function test_cache_settings_is_refreshed_after_value_changed()
    {
        Settings::refreshCache();
        $systemName = Settings::get('system.name');
        $this->assertSame(12, $systemName, 'Initial value should be 12');

        // updating model value
        $setting = Setting::where('key', 'system.name')->firstOrFail();
        $setting->update(['value' => 22]);
        $this->assertDatabaseHas(SettingsConfig::getDatabaseTableName(), [
            'key' => 'system.name',
            'value' => 22,
        ]);

        // new value should be returned
        $systemNameUpdated = Settings::get('system.name');
        $this->assertSame(22, $systemNameUpdated, 'New value should be 22');
    }

    public function test_delete_settings_remove_value_from_cache()
    {
        Settings::refreshCache();
        $systemName = Settings::get('system.name');
        $this->assertSame(12, $systemName, 'Initial value should be 12');

        // delete setting
        $setting = Setting::where('key', 'system.name')->firstOrFail();
        $setting->delete();

        $this->assertDatabaseMissing(SettingsConfig::getDatabaseTableName(), [
            'key' => 'system.name',
        ]);

        $this->expectException(SettingKeyNotFoundException::class);
        Settings::get('system.name');
    }
}