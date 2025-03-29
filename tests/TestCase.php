<?php

namespace XGrz\Settings\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use XGrz\Settings\SettingsServiceProvider;

abstract class TestCase extends Orchestra
{
    protected function getPackageProviders($app)
    {
        return [
            SettingsServiceProvider::class,
        ];
    }
}