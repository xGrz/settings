<?php

namespace XGrz\Settings\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use XGrz\Settings\Helpers\Values\CastValueToType;

class DynamicSettingValueCast implements CastsAttributes
{
    public function get(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        /** @phpstan-ignore property.notFound */
        return CastValueToType::make($value, $model->type);
    }

    public function set(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        /** @phpstan-ignore property.notFound */
        return CastValueToType::make($value, $model->type);
    }
}
