<?php

namespace XGrz\Settings\Helpers\Config;

use Illuminate\Support\Facades\Config;
use XGrz\Settings\Enums\KeyNaming;

class SettingsConfig
{
    public static function getDatabaseTableName(): string
    {
        return Config::get('app-settings.database_table', 'system_settings');
    }

    public static function getCacheKey(): string
    {
        return Config::get('app-settings.cache.key', 'app_settings');
    }

    public static function getCacheTTL(): ?int
    {
        return Config::get('app-settings.cache.ttl', 86400);
    }

    public static function getKeyGeneratorType(): KeyNaming
    {
        $default = KeyNaming::CAMEL_CASE;
        $naming = Config::get('app-settings.key_name_generator', $default);
        if ($naming instanceof KeyNaming) {
            return $naming;
        }

        return KeyNaming::tryFrom($naming) ?? $default;
    }

    public static function getConfigPathFile(): string
    {
        return config_path('app-settings.php');
    }

    public static function getDefinitionsPathFile(): string
    {
        return base_path('settings/definitions.php');
    }

    public static function getRawSettingDefinitions(): array
    {
        return include self::getDefinitionsPathFile();
    }
}
