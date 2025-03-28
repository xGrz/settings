<?php

namespace xGrz\Settings\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

class DynamicSettingValueCast implements CastsAttributes
{
    public function get(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        if (is_null($value)) return null;
        if (empty($model->setting_type)) return $value;
        return $model->setting_type->castValueOnSet($value);
    }

    public function set(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        if (is_null($value)) return null;
        if (empty($model->setting_type)) return $value;
        return $model->setting_type->castValueOnSet($value);
    }
}
