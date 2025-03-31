<?php

namespace XGrz\Settings\Console\Commands;

use Illuminate\Console\Command;

class SettingsPublishLangCommand extends Command
{
    protected $signature = 'settings:publish-lang';

    protected $description = 'Publishes settings language files.';

    public function handle(): void
    {
        $this->newLine();
        $this->call('vendor:publish', ['--tag' => 'settings-lang']);
    }
}
