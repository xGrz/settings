<?php


use XGrz\Settings\Exceptions\SettingKeyNotFoundException;
use XGrz\Settings\Facades\Settings;
use XGrz\Settings\Helpers\InitBaseSettings;
use XGrz\Settings\Models\Setting;
use XGrz\Settings\Tests\TestCase;

class GlobalHelperTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Setting::truncate();
        InitBaseSettings::make();
    }


    public function test_global_helper_returns_setting_value(): void
    {
        Settings::refreshCache();
        $this->expectsDatabaseQueryCount(0);

        $this->assertSame(12, settings('system.name'));
    }

    public function test_global_helper_returns_default_value_when_settings_key_missing_and_default_value_is_set(): void
    {
        Settings::refreshCache();
        $this->expectsDatabaseQueryCount(0);

        $this->assertSame(50, settings('system.name1', 50));
    }

    public function test_global_helper_returns_default_value_when_settings_key_missing_and_default_value_null_is_set(): void
    {
        Settings::refreshCache();
        $this->expectsDatabaseQueryCount(0);

        $this->assertSame(null, settings('system.name1', null));
    }

    public function test_global_helper_throws_exception_when_settings_key_missing_and_default_value_is_not_set(): void
    {
        Settings::refreshCache();
        $this->expectsDatabaseQueryCount(0);
        $this->expectException(SettingKeyNotFoundException::class);

        $this->assertSame(50, settings('system.name1'));
    }


}
