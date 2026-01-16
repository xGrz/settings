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
    protected $signature = 'settings:sync {--silent : Silent mode.} {--force : Force update settings.}';

    protected $description = 'View settings configuration status';

    protected bool $isSilent = false;
    protected bool $isForce = false;

    protected Collection $updatableTable;
    protected Collection $safeUpdatableTable;
    protected Collection $forceUpdatableTable;
    protected Collection $safeUpdatable;
    protected Collection $forceUpdatable;

    private function setUp()
    {
        $this->isSilent = $this->option('silent');
        $this->isForce = $this->option('silent') && $this->option('force');

        $settingItems = new SettingItems();

        $this->updatableTable = $settingItems->getTableBody([Operation::CREATE, Operation::UPDATE, Operation::DELETE, Operation::FORCE_UPDATE]);
        $this->safeUpdatableTable = $settingItems->getTableBody([Operation::CREATE, Operation::UPDATE, Operation::DELETE]);
        $this->forceUpdatableTable = $settingItems->getTableBody([Operation::FORCE_UPDATE]);
        $this->safeUpdatable = $settingItems->getItems([Operation::CREATE, Operation::UPDATE, Operation::DELETE]);
        $this->forceUpdatable = $settingItems->getItems(Operation::FORCE_UPDATE);
    }

    public function handle(): int
    {
        $this->setUp();

        if ($this->updatableTable->isEmpty()) {
            $this->renderNoUpdatesMessage();
            return Command::SUCCESS;
        }

        $this->renderWarning();

        $this->renderTable();

        if ($this->askForSynchronize()) {
            $this->performUpdate($this->safeUpdatable);
        }

        if ($this->askForForceUpdate()) {
            $this->performUpdate($this->forceUpdatable);
        }

        return Command::SUCCESS;
    }

    private function renderNoUpdatesMessage(): void
    {
        if (! $this->isSilent) {
            $this->warn('Settings are already synchronized');
        }
    }

    private function renderWarning(): void
    {
        if ($this->forceUpdatableTable->isNotEmpty() && ! $this->isSilent) {
            $this->components->alert('WARNING! UNSAFE OPERATION! Force update will cause setting value data loss.');
        }
    }

    private function renderTable(): void
    {
        if ($this->updatableTable->isEmpty()) return;
        if ($this->isSilent) return;
        $this->table(SettingItems::getTableHeading(), $this->updatableTable);
    }

    private function askForSynchronize(): ?bool
    {
        if ($this->safeUpdatableTable->isEmpty()) {
            return NULL;
        }

        return $this->isSilent || $this->confirm('Do you want to sync settings?', true);
    }

    private function askForForceUpdate(): ?bool
    {
        if ($this->forceUpdatableTable->isEmpty()) {
            return NULL;
        }

        if ($this->isForce) {
            return true;
        }

        return $this->isSilent || $this->confirm('Do you want to force update settings?');
    }

    private function performUpdate(Collection $settings): void
    {
        if ($settings->isEmpty()) return;
        if ($this->isSilent) {
            $settings->each(function(SettingItem $setting) {
                $setting->performOperation();
            });
        } else {
            progress(
                'Synchronizing settings...',
                $settings,
                function(SettingItem $setting) {
                    Sleep::for(100)->millisecond();
                    return $setting->performOperation();
                }
            );
        }
    }

}
