<?php

namespace XGrz\Settings\Tests\Feature\ConsoleCommands;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use XGrz\Settings\Models\Setting;
use XGrz\Settings\Tests\TestCase;

class ShowCommandTest extends TestCase
{
    public function test_can_show_setting_with_line_parameter()
    {
        $this->artisan('settings:reset', ['--force' => true]);
        $setting = Setting::first();
        Artisan::call('settings:show', ['--key' => $setting->key]);
        $output = Artisan::output();
        $this->assertStringContainsString($setting->key, $output);
        $this->assertStringContainsString($setting->value, $output);
        $this->assertStringContainsString($setting->type->name, $output);
    }

    public function test_shows_error_when_key_not_found()
    {
        Artisan::call('settings:show', ['--key' => Str::random(10)]);
        $output = Artisan::output();
        $this->assertStringContainsString('Setting not found', $output);
    }

    public function test_search_by_key()
    {
        $this->artisan('settings:reset', ['--force' => true]);

        $settingsMatch = Setting::where('key', 'LIKE', "%system%")->orderBy('key')->pluck('key', 'id')->all();

        $this->artisan('settings:show')
            ->expectsSearch(
                'Select a setting to view details:',
                'system.address.city',
                'system',
                $settingsMatch
            );
    }

}