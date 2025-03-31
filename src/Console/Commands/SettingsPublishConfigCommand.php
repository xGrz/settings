<?php

namespace XGrz\Settings\Console\Commands;

use Illuminate\Console\Command;

class SettingsPublishConfigCommand extends Command
{
    protected $signature = 'settings:publish-config';

    protected $description = 'Publishes settings configuration file.';

    public function handle(): void
    {
        $this->newLine();
        $this->call('vendor:publish', ['--tag' => 'settings-config']);
    }
}
