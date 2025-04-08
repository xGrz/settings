<?php

namespace XGrz\Settings\Helpers\Builder;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use XGrz\Settings\Exceptions\ConfigFileNotFoundException;
use XGrz\Settings\Helpers\Config\SettingsConfig;
use XGrz\Settings\ValueObjects\Entry;

class GetSettingsDefinitions
{
    /**
     * This action returns raw array for defined settings
     *
     * @throws ConfigFileNotFoundException
     */
    public static function raw(): array
    {
        return File::exists(base_path('settings/definitions.php'))
            ? include base_path('settings/definitions.php')
            : throw new ConfigFileNotFoundException('Settings definitions file not found. Have you run `php artisan settings:publish`?');
    }

    /**
     * @return Collection<string, Entry>
     *
     * @throws ConfigFileNotFoundException
     */
    public static function asCollection(?array $definitions = NULL): Collection
    {
        return collect(Arr::dot($definitions ?? self::raw()))
            ->filter(fn($value) => $value instanceof Entry)
            ->mapWithKeys(fn(Entry $value, $key) => [SettingsConfig::getKeyGeneratorType()->generateKey($key) => $value])
            ->sortKeys();
    }
}
