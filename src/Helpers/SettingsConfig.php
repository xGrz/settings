<?php

namespace XGrz\Settings\Helpers;

use Exception;
use Illuminate\Support\Facades\File;
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
        $naming = config('app-settings.key_name_generator', $default);
        if ($naming instanceof KeyNaming) {
            return $naming;
        }

        return KeyNaming::tryFrom($naming) ?? $default;
    }

    /**
     * @throws Exception
     */
    public static function getRawSettingsDefinition(): array
    {
        if (!File::exists(base_path('settings/definitions.php'))) {
            throw new Exception('Settings definitions file not found. Have you run `php artisan settings:publish`?');
        }

        return include base_path('settings/definitions.php');
    }
}
