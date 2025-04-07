<?php

namespace XGrz\Settings;

use Illuminate\Support\ServiceProvider;
use XGrz\Settings\Console\Commands\SettingsFormatKeysCommand;
use XGrz\Settings\Console\Commands\SettingShowCommand;
use XGrz\Settings\Console\Commands\SettingsPublishConfigCommand;
use XGrz\Settings\Console\Commands\SettingsResetCommand;
use XGrz\Settings\Console\Commands\SettingsStatusCommand;
use XGrz\Settings\Console\Commands\SettingsSyncCommand;
use XGrz\Settings\Facades\Settings;

class SettingsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(Settings::class, function ($app) {
            return new Settings;
        });
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        }

        $this->loadTranslationsFrom(__DIR__ . '/../lang', 'settings');

        $this->commands([
            SettingsStatusCommand::class,
            SettingsSyncCommand::class,
            SettingsFormatKeysCommand::class,
            SettingsResetCommand::class,
            SettingsPublishConfigCommand::class,
            SettingShowCommand::class,
        ]);

        $this->publishes(
            [
                __DIR__ . '/../config/package-config.php' => config_path('app-definitions.php'),
                __DIR__ . '/../settings/definitions.php' => base_path('settings/definitions.php'),
            ],
            'settings-config'
        );

        $this->publishes([
            __DIR__ . '/../lang' => $this->app->langPath('vendor/settings'),
        ], 'settings-lang');

    }
}
