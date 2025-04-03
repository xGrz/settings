<?php

namespace XGrz\Settings\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
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
        error_reporting(E_ALL & ~E_DEPRECATED);
        ini_set('display_errors', 1);
        parent::setUp();

        $this->artisan('migrate');
    }

    protected function defineEnvironment($app): void
    {
        // Setup default database to use sqlite :memory:
        //        tap($app['config'], function (Repository $config) {
        //            $defaultConfig = include __DIR__ . '/../config/test-config.php';
        //            $config->set('app-settings', $defaultConfig);
        //        });
    }
}
