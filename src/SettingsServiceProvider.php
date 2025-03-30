<?php

namespace XGrz\Settings;

use Illuminate\Support\ServiceProvider;
use XGrz\Settings\Facades\Settings;

class SettingsServiceProvider extends ServiceProvider
{

    public function register(): void
    {
        $this->app->singleton(Settings::class, function ($app) {
            return new Settings();
        });
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        }

        $this->publishes(
            [
                __DIR__ . '/../config/config.php' => config_path('app-settings-config.php'),
                __DIR__ . '/../config/definitions.php' => config_path('app-settings-definitions.php'),
            ],
            'settings'
        );


    }

}
