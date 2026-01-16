<?php

namespace XGrz\Settings\Console\Commands;

use Illuminate\Console\Command;

class SettingsPublishMigrationCommand extends Command
{
    protected $signature = 'settings:publish-migration';

    protected $description = 'Publishes migration from package';

    public function handle(): int
    {
        $this->call('vendor:publish', ['--tag' => 'settings-migrations']);
        return Command::SUCCESS;
    }
}
