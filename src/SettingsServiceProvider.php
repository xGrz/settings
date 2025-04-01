<?php

namespace XGrz\Settings;

use Illuminate\Support\ServiceProvider;
use XGrz\Settings\Console\Commands\SettingsInitCommand;
use XGrz\Settings\Console\Commands\SettingsPublishConfigCommand;
use XGrz\Settings\Console\Commands\SettingsPublishLangCommand;
use XGrz\Settings\Console\Commands\SettingsUpdateKeysCommand;
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

        $this->loadTranslationsFrom(__DIR__ . '/../lang', 'settings');

        $this->commands([
            SettingsInitCommand::class,
            SettingsPublishConfigCommand::class,
            SettingsPublishLangCommand::class,
            SettingsUpdateKeysCommand::class,
        ]);

        $this->publishes(
            [
                __DIR__ . '/../config/package-config.php' => config_path('app-settings.php'),
            ],
            'settings-config'
        );

        $this->publishes([
            __DIR__ . '/../lang' => $this->app->langPath('vendor/settings')
        ], 'settings-lang');



    }

}
