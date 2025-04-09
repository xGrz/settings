<?php

namespace XGrz\Settings\Tests;

use Illuminate\Support\Facades\File;
use Orchestra\Testbench\TestCase as Orchestra;
use XGrz\Settings\Helpers\Config\SettingsConfig;
use XGrz\Settings\SettingsServiceProvider;

abstract class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            SettingsServiceProvider::class,
        ];
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate');
    }

    protected function resetSettingsConfiguration(): void
    {
        File::delete(SettingsConfig::getConfigPathFile());
        File::delete(SettingsConfig::getDefinitionsPathFile());
        $this->artisan('settings:publish-config');
    }

    protected function laravelVersion(): float
    {
        $ver = $this->app->version();
        $ver = explode('.', $ver);
        $mainVer = (int)$ver[0];
        $subVer = (int)$ver[1];
        return $mainVer + ($subVer / 100);
    }
}
