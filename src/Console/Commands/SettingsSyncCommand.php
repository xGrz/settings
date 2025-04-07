<?php

namespace XGrz\Settings\Console\Commands;

use Illuminate\Console\Command;
use XGrz\Settings\Actions\SynchronizeSettingsAction;

class SettingsSyncCommand extends Command
{
    protected $signature = 'settings:sync';

    protected $description = 'View settings configuration status';

    public function handle(): void
    {
        $action = SynchronizeSettingsAction::make();
        if ($action->getTableBody()->isEmpty()) {
            $this->warn('All settings are synchronized.');

            return;
        }

        $this->table($action->getTableHeading(), $action->getTableBody());

        if (!$this->confirm('Do you want to sync settings?', true)) {
            $this->warn('Aborted. No changes were made.');

            return;
        }

        $action->executeWithProgress();

        $this->newLine();
        $this->info('Done');
        $this->newLine();
    }
}
