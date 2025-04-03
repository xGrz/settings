<?php

namespace XGrz\Settings\Console\Commands;

use Illuminate\Console\Command;
use XGrz\Settings\Helpers\DefinitionsHelper;

class SettingsStatusCommand extends Command
{
    protected $signature = 'settings:status';

    protected $description = 'View settings configuration status';

    public function handle(): void
    {
        $helper = new DefinitionsHelper;
        $this->table($helper->heading(), $helper->toListing());
    }
}
