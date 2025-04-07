<?php

namespace XGrz\Settings\Tests\Feature;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use XGrz\Settings\Enums\KeyNaming;
use XGrz\Settings\Exceptions\ConfigFileNotFoundException;
use XGrz\Settings\Helpers\Builder\GetSettingsDefinitions;
use XGrz\Settings\Tests\TestCase;
use XGrz\Settings\ValueObjects\Entry;

class GetSettingsDefinitionsTest extends TestCase
{
    public function test_raw_returns_definitions_file_content()
    {
        $this->artisan('settings:publish-config')
            ->assertExitCode(0);
        $result = GetSettingsDefinitions::raw();

        // Assert that the returned content matches the mocked content
        $this->assertEquals(include base_path('settings/definitions.php'), $result);
        File::delete(base_path('settings/definitions.php'));
    }

    public function test_raw_throws_exception_when_definitions_file_is_missing()
    {
        // Mock the File facade to simulate a missing file
        File::shouldReceive('exists')
            ->with(base_path('settings/definitions.php'))
            ->andReturn(false);

        // Expect the exception
        $this->expectException(ConfigFileNotFoundException::class);
        $this->expectExceptionMessage('Settings definitions file not found. Have you run `php artisan settings:publish`?');

        // Call the method
        GetSettingsDefinitions::raw();
    }

    public function test_as_collection_transforms_definitions_into_collection()
    {
        // Mock definitions
        $definitions = [
            'key1' => Entry::make('value1'),
            'key2' => Entry::make('value2'),
        ];

        $result = GetSettingsDefinitions::asCollection($definitions);

        // Assert result is a collection
        $this->assertInstanceOf(Collection::class, $result);

        // Assert keys and values are as expected
        $this->assertCount(2, $result);
        $this->assertEquals('value1', $result->get('key1')->getValue());
        $this->assertEquals('value2', $result->get('key2')->getValue());
    }

    public function test_as_collection_applies_key_naming_correctly()
    {
        $definitions = [
            'group name.key first' => Entry::make('value1'),
            'group name.key Last' => Entry::make('value2'),
        ];

        Config::set('app-settings.key_name_generator', KeyNaming::CAMEL_CASE);
        $result = GetSettingsDefinitions::asCollection($definitions);
        $this->assertEquals('groupName.keyFirst', $result->keys()->first(), 'Key name should be camelCased');

        Config::set('app-settings.key_name_generator', KeyNaming::SNAKE_CASE);
        $result = GetSettingsDefinitions::asCollection($definitions);
        $this->assertEquals('group_name.key_first', $result->keys()->first(), 'Key name should be snakeCased');

        Config::set('app-settings.key_name_generator', KeyNaming::KEBAB_CASE);
        $result = GetSettingsDefinitions::asCollection($definitions);
        $this->assertEquals('group-name.key-first', $result->keys()->first(), 'Key name should be kebabCased');

    }

    public function test_as_collection_handles_empty_definitions_gracefully()
    {

        $result = GetSettingsDefinitions::asCollection([]);

        $this->assertInstanceOf(Collection::class, $result);
        $this->assertTrue($result->isEmpty());
    }
}
