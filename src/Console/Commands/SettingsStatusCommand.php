<?php

namespace XGrz\Settings\Console\Commands;

use Illuminate\Console\Command;
use XGrz\Settings\Helpers\SettingItems;

class SettingsStatusCommand extends Command
{
    protected $signature = 'settings:status';

    protected $description = 'View settings configuration status';

    public function handle(): void
    {
        $settings = new SettingItems();
        if ($settings->getItems()->isNotEmpty()) {
            $this->table($settings->getTableHeading(), $settings->getTableBody());
        } else {
            $this->warn('Settings not found');
        }
    }
}
