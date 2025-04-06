<?php

namespace XGrz\Settings\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Sleep;
use XGrz\Settings\Helpers\CommandsHelper;
use function Laravel\Prompts\progress;

class SettingsSyncCommand extends Command
{
    protected $signature = 'settings:sync';

    protected $description = 'View settings configuration status';

    public function handle(): void
    {
        $helper = new CommandsHelper;
        if ($helper->synchronizable()->count() === 0) {
            $this->newLine();
            $this->warn('All settings are up to date.');
            $this->newLine();

            return;
        }

        $this->table($helper->heading(), $helper->synchronizable(true));

        if (!$this->confirm('Do you want to sync settings?', true)) {
            $this->warn('Aborted. No changes were made.');

            return;
        }

        progress(
            label: 'Synchronizing settings...',
            steps: $helper->synchronizable(),
            callback: function ($setting, $progress) {
                Sleep::for(100)->millisecond();
                $progress
                    ->label('Synchronizing ' . $setting->key)
                    ->hint('Synchronized ' . $setting->key);

                return $setting->sync();

            },
            hint: 'Waiting for settings to be synchronized...'
        );

        $this->newLine();
        $this->info('Settings synchronized successfully.');
        $this->newLine();
    }
}
