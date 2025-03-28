<?php

namespace xGrz\Settings\Tests\Feature;

use Illuminate\Support\Facades\Config;
use xGrz\Settings\Facades\Settings;
use xGrz\Settings\Models\Setting;
use xGrz\Settings\Tests\TestCase;

class AccessSettingsTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Setting::truncate();
        $defaultConfig = include __DIR__ . '/../../config/definitions.php';
        Config::set('app-settings-definitions', $defaultConfig);
    }

    public function test_can_read_settings()
    {
        $this->assertEquals('abc', Settings::get('system.sellerAddressName'));
    }
}
