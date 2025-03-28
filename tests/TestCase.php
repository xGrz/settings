<?php


namespace xGrz\Settings\Tests;


use Orchestra\Testbench\TestCase as OrchestraTestCase;
use xGrz\Settings\SettingsServiceProvider;

abstract class TestCase extends OrchestraTestCase
{

    protected function setUp(): void
    {
        parent::setUp();
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
    }

    protected function getPackageProviders($app)
    {
        return [
            SettingsServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
        $defaultConfig = include __DIR__ . '/../config/definitions.php';
        $app['config']->set('app-settings-definitions', $defaultConfig);
    }
}
