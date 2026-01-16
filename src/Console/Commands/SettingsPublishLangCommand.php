<?php

namespace XGrz\Settings\Console\Commands;

use Illuminate\Console\Command;

class SettingsPublishLangCommand extends Command
{
    protected $signature = 'settings:publish-lang';

    protected $description = 'Publishes settings lang files';

    public function handle()
    {
        $this->call('vendor:publish', ['--tag' => 'settings-lang']);
        return Command::SUCCESS;
    }
}
