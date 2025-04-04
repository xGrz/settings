<?php

namespace XGrz\Settings\Console\Commands;

use Illuminate\Console\Command;

class SettingsPublishConfigCommand extends Command
{
    protected $signature = 'settings:publish-config';

    protected $description = 'View settings configuration status';

    public function handle(): void
    {
        $this->call('vendor:publish', ['--tag' => 'settings-config']);
    }
}
