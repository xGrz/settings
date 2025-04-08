<?php

namespace XGrz\Settings\Tests\Feature\ConsoleCommands;

use Illuminate\Support\Facades\Config;
use XGrz\Settings\Enums\KeyNaming;
use XGrz\Settings\Enums\Type;
use XGrz\Settings\Models\Setting;
use XGrz\Settings\Tests\TestCase;

class FormatKeysCommandTest extends TestCase
{

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('settings:publish-config');
    }

    private function setGeneratorTo(KeyNaming $type): KeyNaming
    {
        Config::set('app-settings.key_name_generator', $type);
        return $type;
    }

    private function storeTestSetting(): Setting
    {
        return Setting::create(['key' => 'test key. name test', 'type' => Type::STRING, 'value' => 'TestKey'])->refresh();
    }

    public function test_command_formats_keys_can_be_aborted()
    {
        $type = $this->setGeneratorTo(KeyNaming::CAMEL_CASE);

        $this->expectsDatabaseQueryCount(0);

        $this->artisan('settings:format-keys')
            ->expectsQuestion('Are you sure you want to regenerate keys with ' . $type->name . '?', false)
            ->expectsOutputToContain('Aborted')
            ->assertExitCode(1);
    }

    public function test_command_formats_keys_can_change_key_generator_type_to_snake_case()
    {
        $this->setGeneratorTo(KeyNaming::CAMEL_CASE);
        $setting = $this->storeTestSetting();
        $originalKey = $setting->key;
        $type = $this->setGeneratorTo(KeyNaming::SNAKE_CASE);

        $this->artisan('settings:format-keys')
            ->expectsQuestion('Are you sure you want to regenerate keys with ' . $type->name . '?', true)
            ->expectsOutputToContain('Done')
            ->assertExitCode(0);

        $setting->refresh();
        $this->assertNotEquals($originalKey, $setting->key);
        $this->assertEquals('test_key.name_test', $setting->key);
    }

    public function test_command_formats_keys_can_change_key_generator_type_to_kebab_case()
    {
        $this->setGeneratorTo(KeyNaming::CAMEL_CASE);
        $setting = $this->storeTestSetting();
        $originalKey = $setting->key;
        $type = $this->setGeneratorTo(KeyNaming::KEBAB_CASE);

        $this->artisan('settings:format-keys')
            ->expectsQuestion('Are you sure you want to regenerate keys with ' . $type->name . '?', true)
            ->expectsOutputToContain('Done')
            ->assertExitCode(0);

        $setting->refresh();
        $this->assertNotEquals($originalKey, $setting->key);
        $this->assertEquals('test-key.name-test', $setting->key);
    }

    public function test_command_formats_keys_can_change_key_generator_type_to_camel_case()
    {
        $this->setGeneratorTo(KeyNaming::SNAKE_CASE);
        $setting = $this->storeTestSetting();
        $originalKey = $setting->key;
        $type = $this->setGeneratorTo(KeyNaming::CAMEL_CASE);

        $this->artisan('settings:format-keys')
            ->expectsQuestion('Are you sure you want to regenerate keys with ' . $type->name . '?', true)
            ->expectsOutputToContain('Done')
            ->assertExitCode(0);

        $setting->refresh();
        $this->assertNotEquals($originalKey, $setting->key);
        $this->assertEquals('testKey.nameTest', $setting->key);
    }

    public function test_command_formats_keys_updates_cache()
    {
        $this->setGeneratorTo(KeyNaming::SNAKE_CASE);
        $setting = $this->storeTestSetting();
        $originalKey = $setting->key;
        $this->assertSame('TestKey', settings($originalKey));

        $type = $this->setGeneratorTo(KeyNaming::KEBAB_CASE);
        $this->artisan('settings:format-keys')
            ->expectsQuestion('Are you sure you want to regenerate keys with ' . $type->name . '?', true);

        $this->assertSame('TestKey', settings('test-key.name-test'));
    }
}