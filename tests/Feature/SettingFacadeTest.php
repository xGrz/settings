<?php

use XGrz\Settings\Models\Setting;
use XGrz\Settings\Tests\TestCase;

class SettingFacadeTest extends TestCase
{
    public function test_can_get_settings()
    {
        $this->artisan('settings:publish-config');
        $this->artisan('settings:reset')
            ->expectsConfirmation('Are you sure you want to reset all settings?', 'yes');

        $this->assertEquals(8, Setting::count());  // fix this
    }
}
