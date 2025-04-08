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
        parent::setUp();
        $this->artisan('migrate');
    }

    protected function defineEnvironment($app): void
    {
    }
}
