<?php

namespace XGrz\Settings\Helpers;

class SettingsConfig
{
    public static function getDatabaseTableName(): string
    {
        return config('app-settings.database_table', 'system_settings');
    }

    public static function getCacheKey(): ?string
    {
        return config('app-settings.cache.key', 'app_settings');
    }

    public static function getCacheTTL(): ?int
    {
        return config('app-settings.cache.ttl', 86400);
    }
}
