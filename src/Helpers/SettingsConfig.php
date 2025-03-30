<?php

namespace XGrz\Settings\Helpers;

class SettingsConfig
{
    public static function getDatabaseTableName(): string
    {
        return 'system_settings';
    }

    public static function getCacheKey(): ?string
    {
        return cache('settings.cache.key', 'app_settings');
    }

    public static function getCacheTTL(): ?int
    {
        return cache('settings.cache.ttl', null);
    }
}
