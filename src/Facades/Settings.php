<?php

namespace XGrz\Settings\Facades;

use Throwable;
use XGrz\Settings\Exceptions\SettingKeyNotFoundException;
use XGrz\Settings\Models\Setting;

/**
 * @see SettingsService
 */
class Settings
{
    private array $settings = [];

    public function __construct()
    {
        $this->settings = Setting::all()->pluck('value', 'key')->toArray();
    }

    private static function getInstance(): Settings
    {
        return app(Settings::class);
    }

    /**
     * @throws Throwable
     */
    public static function get(string $key)
    {
        $settings = self::getSettings();
        throw_if(!array_key_exists($key, $settings), new SettingKeyNotFoundException('Setting key "' . $key . '" not found'));
        return $settings[$key];
    }

    public static function getSettings(): array
    {
        return self::getInstance()->settings;
    }
}
