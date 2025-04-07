<?php

namespace XGrz\Settings\Facades;

use Arr;
use XGrz\Settings\Enums\Type;
use XGrz\Settings\Exceptions\SettingKeyNotFoundException;
use XGrz\Settings\Helpers\SettingsConfig;
use XGrz\Settings\Models\Setting;

class Settings
{
    private array $settings = [];

    public function __construct()
    {
        self::getSettings();
    }

    private function getSettings(): array
    {
        if (count($this->settings)) return $this->settings;

        $this->settings = cache()->remember(
            SettingsConfig::getCacheKey(),
            SettingsConfig::getCacheTTL(),
            fn() => Setting::all()->pluck('value', 'key')->toArray()
        );
        return $this->settings;
    }

    private static function getInstance(): Settings
    {
        return app(Settings::class);
    }

    public static function all(): array
    {
        return self::getInstance()->getSettings();
    }


    /**
     * @throws SettingKeyNotFoundException
     */
    public static function get(string $key)
    {
        if (array_key_exists($key, self::getInstance()->getSettings())) {
            return self::getInstance()->getSettings()[$key];
        }
        if (str($key)->endsWith('.')) {
            $key = str($key)->replaceEnd('.', '')->toString();
            $multiple = [];
            foreach (self::getInstance()->getSettings() as $keyName => $value) {
                if (str($keyName)->startsWith($key)) {
                    $partialKey = str($keyName)->replaceStart($key, '')->replaceStart('.', '')->toString();
                    if (! empty($partialKey)) {
                        $multiple[$partialKey] = $value;
                    }
                }
            }
            if (! empty($multiple)) {
                return Arr::undot($multiple);
            }
        }

        throw new SettingKeyNotFoundException('Setting key [' . $key . '] not found');
    }

    private function resetSettings(): void
    {
        $this->settings = [];
    }

    public static function invalidateCache(): void
    {
        cache()->forget(SettingsConfig::getCacheKey());
        self::getInstance()->resetSettings();
    }

    public static function refreshCache(): void
    {
        self::invalidateCache();
        self::getInstance()->getSettings();
    }

    public static function getTypes()
    {
        $types = [];
        foreach (Type::cases() as $type) {
            $types[$type->value] = $type->getLabel();
        }
        return $types;
    }

}
