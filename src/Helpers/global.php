<?php

use XGrz\Settings\Exceptions\SettingKeyNotFoundException;
use XGrz\Settings\Facades\Settings;

if (!function_exists('settings')) {

    /**
     * @param mixed ...$params
     * - (string $keyName) - search for setting key name
     * - (mixed $defaultValue) - default value when setting key is missing
     * @return int|float|bool|string|null
     * @throws SettingKeyNotFoundException
     * @throws Throwable
     */
    function settings(...$params): int|float|bool|null|string|array
    {
        if (count($params) === 0) {
            throw new SettingKeyNotFoundException('Please provide keyName as a parameter');
        }
        $key = $params[0];
        try {
            return Settings::get($key);
        } catch (SettingKeyNotFoundException $ex) {
            if (array_key_exists(1, $params)) {
                return $params[1];
            }
            throw $ex;
        }
    }

}