<?php

namespace XGrz\Settings\Console\Commands;

use Illuminate\Console\Command;
use XGrz\Settings\Facades\Settings;
use XGrz\Settings\Models\Setting;

class SettingsUpdateKeysCommand extends Command
{
    protected $signature = 'settings:update-keys';

    protected $description = 'Update keys after changing config key generation type.';

    public function handle(): int
    {
        $confirmation = $this->confirm('Are you sure you want to update keys?', true);

        if (!$confirmation) {
            $this->warn('Aborted. Keys not updated.');
            return 254;
        }
        Setting::all()
            ->each(fn(Setting $setting) => $setting->update([
                'prefix' => str($setting->prefix)->append('.'),
                'suffix' => str($setting->suffix)->append('.'),
            ]));

        Settings::refreshCache();
        return 0;
    }
}
