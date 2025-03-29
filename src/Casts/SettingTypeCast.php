<?php

namespace XGrz\Settings\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use XGrz\Settings\Enums\SettingType;

class SettingTypeCast implements CastsAttributes
{
    public function get(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        return SettingType::tryFrom($value);
    }

    public function set(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        if (is_null($value)) return null;
        if (is_null($model->getOriginal($key))) return $value->value;

        return $model->getOriginal($key)->canBeChangedTo($value)
            ? $value->value
            : $model->getOriginal($key)->value;
    }
}
