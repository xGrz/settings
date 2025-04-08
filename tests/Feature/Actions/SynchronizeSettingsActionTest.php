<?php

namespace XGrz\Settings\Tests\Feature\Actions;

use XGrz\Settings\Actions\SynchronizeSettingsAction;
use XGrz\Settings\Helpers\Builder\GetSettingsDefinitions;
use XGrz\Settings\Helpers\SettingsConfig;
use XGrz\Settings\Tests\TestCase;

class SynchronizeSettingsActionTest extends TestCase
{

    public function test_can_list_defined_settings()
    {
        $definitionsCount = GetSettingsDefinitions::asCollection()->count();
        $synchronizableDefinitionsCount = SynchronizeSettingsAction::make()->getTableBody()->count();

        $this->assertGreaterThan(0, $definitionsCount, 'Definitions not found.');
        $this->assertEquals($definitionsCount, $synchronizableDefinitionsCount);
    }

    public function test_can_create_settings_on_synchronize_with_on_empty_database()
    {
        $this->assertDatabaseEmpty(SettingsConfig::getDatabaseTableName());
        $action = SynchronizeSettingsAction::make();
        $rowsCount = $action->getTableBody()->count();
        $action->execute();

        $this->assertDatabaseCount(SettingsConfig::getDatabaseTableName(), $rowsCount);
    }
}