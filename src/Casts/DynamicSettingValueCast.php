<?php

namespace XGrz\Settings\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

class DynamicSettingValueCast implements CastsAttributes
{
    public function get(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        if (is_null($value)) return null;
        if (empty($model->type)) return $value;
        return $model->type->castValue($value);
    }

    public function set(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        if (is_null($value)) return null;
        if (empty($model->type)) return $value;
        return $model->type->castValue($value);
    }
}
