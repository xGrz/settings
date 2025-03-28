<?php

namespace xGrz\Settings\Facades;

use Illuminate\Support\Facades\Facade;
use xGrz\Settings\Services\SettingsService;

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
