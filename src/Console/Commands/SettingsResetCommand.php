<?php

namespace XGrz\Settings\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Sleep;
use XGrz\Settings\Helpers\DefinitionsHelper;
use XGrz\Settings\Models\Setting;

use function Laravel\Prompts\progress;

class SettingsResetCommand extends Command
{
    protected $signature = 'settings:reset';

    protected $description = 'View settings configuration status';

    public function handle(): void
    {
        Setting::truncate();

        if (!$this->confirm('Do you want to reset all settings?', false)) {
            $this->warn('Aborted. No changes were made.');
            $this->newLine();

            return;
        }

        $helper = new DefinitionsHelper;
        progress(
            label: 'Reset settings...',
            steps: $helper->synchronizable(),
            callback: function ($setting, $progress) {
                Sleep::for(100)->millisecond();
                $progress
                    ->label('Creating ' . $setting->key)
                    ->hint('Created ' . $setting->key);

                return $setting->sync();

            }
        );

        $this->newLine();
        $this->info('Reset settings completed successfully.');
        $this->newLine();
        $this->call('settings:status');
    }
}
