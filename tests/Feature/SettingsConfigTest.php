<?php

namespace XGrz\Settings\Tests\Feature;

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
}