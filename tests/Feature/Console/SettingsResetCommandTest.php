<?php

namespace XGrz\Settings\Tests\Feature\Console;

use XGrz\Settings\Actions\CreateSettingsAction;
use XGrz\Settings\Tests\TestCase;

class SettingsResetCommandTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate');
        $this->artisan('settings:publish-config');
    }

    public function test_can_reset_settings()
    {
        $entriesCount = CreateSettingsAction::make()->getTableBody()->count();
        CreateSettingsAction::make()->execute();

        $this->expectsDatabaseQueryCount($entriesCount + 4);
        $this->artisan('settings:reset')
            ->expectsConfirmation('Are you sure you want to reset all settings?', 'yes')
            ->assertExitCode(0);
    }

    public function test_can_abort_resetting_settings()
    {
        $this->expectsDatabaseQueryCount(0);
        $this->artisan('settings:reset')
            ->expectsConfirmation('Are you sure you want to reset all settings?', 'no')
            ->expectsOutput('Aborted. No changes were made.')
            ->assertExitCode(1);
    }
}