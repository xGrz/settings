<?php

namespace XGrz\Settings\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Sleep;
use XGrz\Settings\Enums\Operation;
use XGrz\Settings\Helpers\SettingItems;
use XGrz\Settings\Models\Setting;
use XGrz\Settings\ValueObjects\SettingItem;
use function Laravel\Prompts\progress;

class SettingsResetCommand extends Command
{
    protected $signature = 'settings:reset {--force : Force reset settings.}';

    protected $description = 'View settings configuration status';

    public function handle(): int
    {
        if (! $this->option('force') && ! $this->confirm('Are you sure you want to reset all settings?', false)) {
            $this->warn('Aborted. No changes were made.');
            $this->newLine();

            return 1;
        }

        Setting::truncate();

        $settings = (new SettingItems())->getItems(Operation::CREATE);
        if ($settings->isNotEmpty()) {
            progress(
                'Resetting settings...',
                $settings,
                function(SettingItem $setting, $progress) {
                    Sleep::for(100)->millisecond();
                    $progress
                        ->label('Processing ' . $setting->key)
                        ->hint('Processed ' . $setting->key);
                    return $setting->performOperation();
                },
            );
        }

        $this->newLine();
        $this->info('Reset settings completed successfully.');
        $this->newLine();
        $this->call('settings:status');
        return 0;
    }
}
