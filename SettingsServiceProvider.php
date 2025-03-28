<?php

namespace xGrz\Settings;

use Illuminate\Support\ServiceProvider;
use xGrz\Settings\Services\SettingsService;


class SettingsServiceProvider extends ServiceProvider
{

    public function register(): void
    {
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        }


        $this->app->singleton(SettingsService::class, fn() => new SettingsService());
    }

}
