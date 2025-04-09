<?php

namespace XGrz\Settings\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use XGrz\Settings\Models\Setting;

class SettingsFormatKeysCommand extends Command
{
    protected $signature = 'settings:format-keys';

    protected $description = 'Format keys';

    public function handle(): int
    {
        Cache::clear();
        $type = Config::get('app-settings.key_name_generator')->name;
        if (! $this->confirm('Are you sure you want to regenerate keys with ' . $type . '?')) {
            $this->warn('Aborted. No changes were made.');

            return 1;
        }

        Setting::all()->each(function(Setting $setting) {
            $setting->refreshKey();
        });

        $this->call('settings:status');
        $this->info('Done');
        return 0;
    }
}
