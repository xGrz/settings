<?php

namespace XGrz\Settings\Console\Commands;

use Illuminate\Console\Command;
use XGrz\Settings\Helpers\DefinitionsHelper;

class SettingsSyncCommand extends Command
{
    protected $signature = 'settings:update';

    protected $description = 'View settings configuration status';

    public function handle(): void
    {
        $helper = new DefinitionsHelper;
        if ($helper->synchronizable()->count() === 0) {
            $this->warn('No settings to sync.');

            return;
        }

        $this->table($helper->heading(), $helper->synchronizable(true));

        if (!$this->confirm('Do you want to sync settings?', true)) {
            $this->warn('Aborted. No changes were made.');

            return;
        }

    }
}
