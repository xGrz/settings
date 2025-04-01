<?php

namespace XGrz\Settings\Tests\Feature;

use XGrz\Settings\Enums\KeyNaming;
use XGrz\Settings\Helpers\SettingsConfig;
use XGrz\Settings\Tests\TestCase;

class SettingsConfigTest extends TestCase
{
    public function test_can_read_database_table_name()
    {
        $this->assertSame(
            'application_settings',
            config('app-settings.database_table')
        );
    }

    public function test_can_receive_database_table_name_by_config_class_method()
    {
        $this->assertSame(
            config('app-settings.database_table'),
            SettingsConfig::getDatabaseTableName()
        );
    }

    public function test_can_read_cache_key()
    {
        $this->assertSame(
            'app-settings-test',
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
        $this->assertSame(
            10,
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
        config(['app-settings.preferred_key_type' => null]);

        $this->assertSame(
            KeyNaming::CAMEL_CASE,
            SettingsConfig::getKeyGeneratorType()
        );
    }

    public function test_can_receive_key_generator_type_from_config()
    {
        config(['app-settings.preferred_key_type' => KeyNaming::SNAKE_CASE->value]);

        $this->assertSame(
            KeyNaming::SNAKE_CASE,
            SettingsConfig::getKeyGeneratorType()
        );
    }

    public function test_invalid_key_generator_type_resolves_to_default()
    {
        config(['app-settings.preferred_key_type' => 'invalidType']);

        $this->assertSame(
            KeyNaming::CAMEL_CASE,
            SettingsConfig::getKeyGeneratorType()
        );
    }

    public function test_can_receive_overridden_key_generator_type()
    {
        config(['app-settings.preferred_key_type' => KeyNaming::SNAKE_CASE]);

        $this->assertSame(
            KeyNaming::SNAKE_CASE,
            SettingsConfig::getKeyGeneratorType()
        );
    }
}