<?php

namespace XGrz\Settings\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use XGrz\Settings\Helpers\SettingsConfig;

class KeyNameCast implements CastsAttributes
{
    public function get(?Model $model, string $key, mixed $value, array $attributes): mixed
    {
        return $value;
    }

    public function set(?Model $model, string $key, mixed $value, array $attributes): string
    {
        return SettingsConfig::getKeyGeneratorType()
            ->generateKey($value);
    }
}
