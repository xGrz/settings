<?php

namespace XGrz\Settings\Facades;

use Illuminate\Support\Facades\Facade;
use XGrz\Settings\Services\SettingsService;

/**
 * @see SettingsService
 */
class Settings extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return SettingsService::class;
    }

    public static function get(string $key)
    {
        return SettingsService::get($key);
    }
}
