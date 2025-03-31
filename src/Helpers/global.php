<?php

use XGrz\Settings\Exceptions\SettingKeyNotFoundException;
use XGrz\Settings\Facades\Settings;

if (!function_exists('settings')) {

    function settings(string $key, $default = null): int|float|bool|null|string
    {
        if (!is_null($default)) {
            try {
                $settingValue = Settings::get($key);
            } catch (SettingKeyNotFoundException $ex) {
                $settingValue = $default;
            }
            return $settingValue;
        }
        return Settings::get($key);

    }

}