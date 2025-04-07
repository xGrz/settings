<?php

namespace XGrz\Settings\Console\Commands;

use Illuminate\Console\Command;
use XGrz\Settings\Actions\ListSettingsAction;

class SettingsStatusCommand extends Command
{
    protected $signature = 'settings:status';

    protected $description = 'View settings configuration status';

    public function handle(): void
    {
        $action = ListSettingsAction::make();
        $this->table($action->getTableHeading(), $action->getTableBody());
    }
}
