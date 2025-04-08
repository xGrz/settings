<?php

namespace Console;

use Illuminate\Support\Facades\Artisan;
use Symfony\Component\Console\Tester\CommandTester;
use XGrz\Settings\Actions\CreateSettingsAction;
use XGrz\Settings\Console\Commands\SettingShowCommand;
use XGrz\Settings\Models\Setting;
use XGrz\Settings\Tests\TestCase;

class SettingsShowCommandTest extends TestCase
{
    protected CommandTester $commandTester;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate');
        $this->artisan('settings:publish-config');
        CreateSettingsAction::make()->execute();
        $command = $this->app->make(SettingShowCommand::class);
        $this->commandTester = new CommandTester($command);
    }

    public function test_can_show_setting_with_line_parameter()
    {
        $setting = Setting::first();
        Artisan::call('settings:show', ['--key' => $setting->key]);
        $output = Artisan::output();
        $this->assertStringContainsString($setting->key, $output);
        $this->assertStringContainsString($setting->value, $output);
        $this->assertStringContainsString($setting->type->name, $output);
    }

}