<?php

namespace XGrz\Settings;

use Illuminate\Support\ServiceProvider;
use XGrz\Settings\Console\Commands\SettingsFormatKeysCommand;
use XGrz\Settings\Console\Commands\SettingsPublishConfigCommand;
use XGrz\Settings\Console\Commands\SettingsPublishLangCommand;
use XGrz\Settings\Console\Commands\SettingsPublishMigrationCommand;
use XGrz\Settings\Console\Commands\SettingsResetCommand;
use XGrz\Settings\Console\Commands\SettingsShowCommand;
use XGrz\Settings\Console\Commands\SettingsStatusCommand;
use XGrz\Settings\Console\Commands\SettingsSyncCommand;
use XGrz\Settings\Facades\Settings;

class SettingsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(Settings::class, function($app) {
            return new Settings;
        });
    }

    public function boot(): void
    {
        $this->commands([
            SettingsStatusCommand::class,
            SettingsSyncCommand::class,
            SettingsFormatKeysCommand::class,
            SettingsResetCommand::class,
            SettingsPublishConfigCommand::class,
            SettingsPublishLangCommand::class,
            SettingsPublishMigrationCommand::class,
            SettingsShowCommand::class,
        ]);

        $this->loadTranslationsFrom(__DIR__ . '/../lang', 'settings');

        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        if ($this->app->runningInConsole()) {
            $this->publishes(
                [
                    __DIR__ . '/../config/package-config.php' => config_path('app-settings.php'),
                    __DIR__ . '/../settings/definitions.php' => base_path('settings/definitions.php'),
                ],
                'settings-config'
            );
            $this->publishes([
                __DIR__ . '/../lang' => $this->app->langPath('vendor/settings'),
            ], 'settings-lang');

            $this->publishes([
                __DIR__ . '/../database/migrations' => $this->app->databasePath('migrations'),
            ], 'settings-migrations');
        }
    }

}
