<?php

use XGrz\Settings\Exceptions\SettingKeyNotFoundException;
use XGrz\Settings\Facades\Settings;

if (! function_exists('settings')) {
    /**
     * @param string|null $key
     * @param mixed       ...$defaultValue
     * @return int|float|bool|string|array|null
     *
     * @throws SettingKeyNotFoundException
     */
    function settings(?string $key = NULL, ...$defaultValue): int|float|bool|null|string|array
    {
        if (empty($key)) {
            throw new SettingKeyNotFoundException('Please provide keyName as a parameter');
        }

        try {
            return Settings::get($key);
        } catch (SettingKeyNotFoundException $ex) {
            if (array_key_exists(0, $defaultValue)) {
                return $defaultValue[0];
            }
            throw $ex;
        }
    }
}

if (! function_exists('setSetting')) {
    /**
     * @throws SettingKeyNotFoundException
     */
    function setSetting(string $key, mixed $value): void
    {
        Settings::set($key, $value);
    }
}

