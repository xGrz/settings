<?php

namespace XGrz\Settings\Helpers;

use XGrz\Settings\Enums\KeyNaming;

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

    public static function getKeyGeneratorType(): KeyNaming
    {
        $default = KeyNaming::CAMEL_CASE;
        $naming = config('app-settings.preferred_key_type', $default);
        if ($naming instanceof KeyNaming) return $naming;

        return KeyNaming::tryFrom($naming) ?? $default;
    }
}
