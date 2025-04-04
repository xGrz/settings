<?php

use XGrz\Settings\Facades\Settings;
use XGrz\Settings\Models\Setting;
use XGrz\Settings\Tests\TestCase;

class SettingFacadeTest extends TestCase
{

    public function test_can_get_settings()
    {
        $this->artisan('settings:publish-config');
        $this->artisan('settings:reset')
            ->expectsConfirmation('Are you sure you want to reset all settings?', true);

        dd(Setting::count());
    }
}