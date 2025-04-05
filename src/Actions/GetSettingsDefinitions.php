<?php

namespace XGrz\Settings\Actions;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use XGrz\Settings\Exceptions\ConfigFileNotFoundException;
use XGrz\Settings\Helpers\SettingsConfig;
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
    public static function asCollection(array $definitions = []): Collection
    {
        return collect(Arr::dot(empty($definitions) ? self::raw() : $definitions))
            ->filter(fn($value) => $value instanceof Entry)
            ->mapWithKeys(fn(Entry $value, $key) => [SettingsConfig::getKeyGeneratorType()->generateKey($key) => $value])
            ->sortKeys();
    }
}
