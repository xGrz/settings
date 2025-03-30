<?php

namespace XGrz\Settings\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

class KeyNameCast implements CastsAttributes
{
    public function get(?Model $model, string $key, mixed $value, array $attributes): mixed
    {
        return $value;
    }

    public function set(?Model $model, string $key, mixed $value, array $attributes): mixed
    {
        return str($value)->camel()->toString();
    }
}
