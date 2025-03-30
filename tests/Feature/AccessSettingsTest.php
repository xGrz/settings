<?php

namespace XGrz\Settings\Tests\Feature;

use XGrz\Settings\Exceptions\SettingKeyNotFoundException;
use XGrz\Settings\Facades\Settings;
use XGrz\Settings\Helpers\InitBaseSettings;
use XGrz\Settings\Models\Setting;
use XGrz\Settings\Tests\TestCase;

class AccessSettingsTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Setting::truncate();
        InitBaseSettings::make();
    }

    public function test_setting_not_found_throws_exception()
    {
        $this->expectException(SettingKeyNotFoundException::class);
        Settings::get('not-found');
    }

    public function test_can_read_settings()
    {
        $this->assertEquals('Laravel Corporation', Settings::get('system.sellerAddressName'));
        $this->assertEquals('00-950', Settings::get('system.sellerAddressPostalCode'));
        $this->assertEquals(10, Settings::get('pageLength.default'));
    }

    public function test_single_request_to_database()
    {
        Settings::invalidateCache();
        $this->expectsDatabaseQueryCount(1);
        Settings::get('system.sellerAddressName');
        Settings::get('system.sellerAddressPostalCode');
    }
}
