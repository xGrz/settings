<?php

namespace XGrz\Settings\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Sleep;
use XGrz\Settings\Enums\Operation;
use XGrz\Settings\Helpers\SettingItems;
use XGrz\Settings\ValueObjects\SettingItem;
use function Laravel\Prompts\progress;

class SettingsSyncCommand extends Command
{
    protected $signature = 'settings:sync';

    protected $description = 'View settings configuration status';


    public function handle(): int
    {
        $settingItems = new SettingItems();

        $updatableTable = $settingItems->getTableBody([Operation::CREATE, Operation::UPDATE, Operation::DELETE, Operation::FORCE_UPDATE]);
        $safeUpdatableTable = $settingItems->getTableBody([Operation::CREATE, Operation::UPDATE, Operation::DELETE]);
        $forceUpdatableTable = $settingItems->getTableBody([Operation::FORCE_UPDATE]);
        $safeUpdatable = $settingItems->getItems([Operation::CREATE, Operation::UPDATE, Operation::DELETE]);
        $forceUpdatable = $settingItems->getItems(Operation::FORCE_UPDATE);

        if ($updatableTable->isEmpty()) {
            $this->warn('Settings are already synchronized');

            return 1;
        }

        if ($forceUpdatableTable->isNotEmpty()) {
            $this->components->alert('WARNING! UNSAFE OPERATION! Force update will cause value data loss.');
        }

        $this->table(SettingItems::getTableHeading(), $updatableTable);

        if ($safeUpdatableTable->isNotEmpty()) {
            if ($this->askForSynchronize($settingItems)) {
                $this->performUpdate($safeUpdatable);
            }
        }

        if ($forceUpdatableTable->isNotEmpty()) {
            if ($this->askForForceUpdate($settingItems)) {
                $this->performUpdate($forceUpdatable);
            }
        }
        return 0;
    }


    private function askForSynchronize(SettingItems $settingItems): ?bool
    {
        $settings = $settingItems->getTableBody([Operation::CREATE, Operation::UPDATE, Operation::DELETE]);
        if ($settings->isEmpty()) return NULL;

        return $this->confirm('Do you want to sync settings?', true);
    }

    private function askForForceUpdate(SettingItems $settingItems): ?bool
    {
        $settings = $settingItems->getTableBody([Operation::FORCE_UPDATE]);
        if ($settings->isEmpty()) return NULL;
        $this->newLine();
        return $this->confirm('Do you want to force update settings?');
    }

    private function performUpdate(Collection $settings): void
    {
        progress(
            'Syncing settings...',
            $settings,
            function(SettingItem $setting) {
                Sleep::for(100)->millisecond();
                return $setting->performOperation();
            }
        );
    }

}
