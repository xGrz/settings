<?php

namespace XGrz\Settings\Console\Commands;

use Illuminate\Console\Command;
use XGrz\Settings\Helpers\DefinitionsHelper;

class SettingsUpdateCommand extends Command
{
    protected $signature = 'settings:update';

    protected $description = 'View settings configuration status';

    public function handle(): void
    {
        $helper = new DefinitionsHelper;
        $this->table($helper->heading(), $helper->toListing());
    }
}
