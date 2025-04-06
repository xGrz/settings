<?php

namespace XGrz\Settings\Tests\Feature;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use XGrz\Settings\Models\Setting;
use XGrz\Settings\Tests\TestCase;

class SettingsFacadeTest extends TestCase
{
    protected function setUp(): void
    {
        Config::set('app-settings', include __DIR__ . '/../../config/package-config.php');
        parent::setUp();

    }

    public function test_can_read_setting_by_key()
    {
        $setting = Setting::inRandomOrder()->firstOrFail();
        $this->assertEquals($setting->value, settings($setting->key));
    }

    protected function tearDown(): void
    {
        Cache::clear();
        parent::tearDown();
    }
}
