<?php

namespace XGrz\Settings\Facades;

use Throwable;
use XGrz\Settings\Exceptions\SettingKeyNotFoundException;
use XGrz\Settings\Helpers\SettingsConfig;
use XGrz\Settings\Models\Setting;

/**
 * @see SettingsService
 */
class Settings
{

    private array $settings = [];

    public function __construct()
    {
        $this->load();
    }

    private function load(): void
    {
        $this->settings = cache()
            ->remember(
                SettingsConfig::getCacheKey(),
                SettingsConfig::getCacheTTL(),
                fn() => Setting::all()->pluck('value', 'key')->toArray()
            );
    }

    private static function getInstance(): Settings
    {
        return app(Settings::class);
    }

    private function getSettings(): array
    {
        if (empty($this->settings)) {
            $this->load();
        }
        return $this->settings;
    }

    /**
     * @throws Throwable
     */
    public static function get(string $key)
    {
        $settings = self::all();
        throw_if(!array_key_exists($key, $settings), new SettingKeyNotFoundException('Setting key "' . $key . '" not found'));
        return $settings[$key];
    }

    public static function all(): array
    {
        return self::getInstance()->getSettings();
    }

    public static function invalidateCache(): void
    {
        cache()->forget(SettingsConfig::getCacheKey());
        self::getInstance()->settings = [];
    }

    public static function refreshCache(): void
    {
        self::invalidateCache();
        self::getInstance()->load();
    }
}
