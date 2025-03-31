<?php

namespace XGrz\Settings\Console\Commands;

use Illuminate\Console\Command;
use XGrz\Settings\Facades\Settings;
use XGrz\Settings\Helpers\InitBaseSettings;

class SettingsInitCommand extends Command
{
    protected $signature = 'settings:init';

    protected $description = 'Initialize settings from config file';

    public function handle(): void
    {
        InitBaseSettings::make();
        Settings::refreshCache();
        $settingsCount = count(Settings::all());
        $this->info('Settings have been initialized.');
        $this->info($settingsCount . ' keys added');
    }
}
