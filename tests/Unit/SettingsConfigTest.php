<?php

namespace XGrz\Settings\Tests\Unit;

use Illuminate\Support\Facades\Config;
use XGrz\Settings\Enums\KeyNaming;
use XGrz\Settings\Helpers\Config\SettingsConfig;
use XGrz\Settings\Tests\TestCase;

class SettingsConfigTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Config::set('app-settings.database_table', 'application_settings-test');
        Config::set('app-settings.cache', [
            'key' => 'cache_key',
            'ttl' => 20,
        ]);
        Config::set('app-settings.key_name_generator', KeyNaming::CAMEL_CASE);
    }

    public function test_can_read_database_table_name()
    {
        $this->assertEquals(
            'application_settings-test',
            config('app-settings.database_table')
        );
    }

    public function test_can_receive_database_table_name_by_config_class_method()
    {
        $this->assertEquals(
            config('app-settings.database_table'),
            SettingsConfig::getDatabaseTableName()
        );
    }

    public function test_can_read_cache_key()
    {
        $this->assertSame(
            'cache_key',
            config('app-settings.cache.key')
        );
    }

    public function test_can_receive_cache_key_by_config_class_method()
    {
        $this->assertSame(
            config('app-settings.cache.key'),
            SettingsConfig::getCacheKey()
        );
    }

    public function test_can_read_cache_ttl()
    {
        $this->assertSame(20,
            config('app-settings.cache.ttl')
        );
    }

    public function test_can_receive_cache_ttl_by_config_class_method()
    {
        $this->assertSame(
            config('app-settings.cache.ttl'),
            SettingsConfig::getCacheTTL()
        );
    }

    public function test_can_receive_default_key_generator_type()
    {
        config(['app-settings.key_name_generator' => NULL]);

        $this->assertSame(
            KeyNaming::CAMEL_CASE,
            SettingsConfig::getKeyGeneratorType()
        );
    }

    public function test_can_receive_key_generator_type_from_config()
    {
        config(['app-settings.key_name_generator' => KeyNaming::SNAKE_CASE->value]);

        $this->assertSame(
            KeyNaming::SNAKE_CASE,
            SettingsConfig::getKeyGeneratorType()
        );
    }

    public function test_invalid_key_generator_type_resolves_to_default()
    {
        config(['app-settings.key_name_generator' => 'invalidType']);

        $this->assertSame(
            KeyNaming::CAMEL_CASE,
            SettingsConfig::getKeyGeneratorType()
        );
    }

    public function test_can_receive_overridden_key_generator_type()
    {
        config(['app-settings.key_name_generator' => KeyNaming::SNAKE_CASE]);

        $this->assertSame(
            KeyNaming::SNAKE_CASE,
            SettingsConfig::getKeyGeneratorType()
        );
    }
}
