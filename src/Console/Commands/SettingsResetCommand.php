<?php

namespace XGrz\Settings\Console\Commands;

use Illuminate\Console\Command;
use XGrz\Settings\Actions\CreateSettingsAction;
use XGrz\Settings\Models\Setting;

class SettingsResetCommand extends Command
{
    protected $signature = 'settings:reset';

    protected $description = 'View settings configuration status';

    public function handle(): int
    {
        if (! $this->confirm('Are you sure you want to reset all settings?', false)) {
            $this->warn('Aborted. No changes were made.');
            $this->newLine();

            return 1;
        }

        Setting::truncate();
        CreateSettingsAction::make()->executeWithProgress();

        $this->newLine();
        $this->info('Reset settings completed successfully.');
        $this->newLine();
        $this->call('settings:status');
        return 0;
    }
}
