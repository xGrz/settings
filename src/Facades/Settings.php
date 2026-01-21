<?php

namespace XGrz\Settings\Facades;

use Arr;
use XGrz\Settings\Enums\Type;
use XGrz\Settings\Exceptions\SettingKeyNotFoundException;
use XGrz\Settings\Helpers\Config\SettingsConfig;
use XGrz\Settings\Helpers\SettingItem;
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

        $settings = (array)cache()->remember(
            SettingsConfig::getCacheKey(),
            SettingsConfig::getCacheTTL(),
            fn() => Setting::get()->mapWithKeys(fn(Setting $setting) => [$setting->key => SettingItem::make($setting)])->toArray()
        );

        $this->settings = $settings;

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

    private static function keyExists(string $key): bool
    {
        return array_key_exists($key, self::getInstance()->getSettings());
    }

    /**
     * @throws SettingKeyNotFoundException
     */
    public static function get(?string $key = NULL)
    {
        if (empty($key)) {
            return collect(self::getInstance()->getSettings())
                ->map(fn(SettingItem $setting) => $setting->value)
                ->toArray();
        }

        if (self::keyExists($key)) {
            return self::getInstance()->getSettings()[$key]->value;
        }

        if (str($key)->endsWith('.')) {
            $key = str($key)->replaceEnd('.', '')->toString();
            $multiple = [];
            foreach (self::getInstance()->getSettings() as $keyName => $settingItem) {
                if (str($keyName)->startsWith($key)) {
                    $partialKey = str($keyName)->replaceStart($key, '')->replaceStart('.', '')->toString();
                    if (! empty($partialKey)) {
                        $multiple[$partialKey] = $settingItem->value;
                    }
                }
            }
            if (! empty($multiple)) {
                return Arr::undot($multiple);
            }
        }

        throw new SettingKeyNotFoundException('Setting key [' . $key . '] not found');
    }

    /**
     * @throws SettingKeyNotFoundException
     */
    public static function set(string $key, string|int|float|null $value): void
    {
        $setting = Setting::firstWhere('key', $key);
        if (empty($setting)) {
            throw new SettingKeyNotFoundException('Setting key [' . $key . '] not found');
        }
        $setting->update(['value' => $value]);
        self::invalidateCache();
    }

    /**
     * @throws SettingKeyNotFoundException
     */
    public static function type(string $key): Type
    {
        if (self::keyExists($key)) {
            return self::getInstance()->getSettings()[$key]->type;
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

    public static function getTypes(): array
    {
        $types = [];
        foreach (Type::cases() as $type) {
            $types[$type->value] = $type->getLabel();
        }
        return $types;
    }

}
