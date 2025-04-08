<?php

namespace ConsoleCommands;

use Illuminate\Support\Facades\File;
use XGrz\Settings\Tests\TestCase;

class PublishLangTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        File::delete(base_path('lang'));
    }

    public function test_can_publish_lang_files()
    {
        $this->artisan('settings:publish-lang')
            ->assertExitCode(0);

        $this->assertFileExists(base_path('lang/vendor/settings/en/label.php'));
        $this->assertFileExists(base_path('lang/vendor/settings/pl/types.php'));
    }
}