<?php

namespace XGrz\Settings\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use XGrz\Settings\Enums\Type;

class DynamicSettingValueCast implements CastsAttributes
{
    public function get(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        if (!isset($model->type)) {
            return $value;
        }

        return self::format($value, $model->type);
    }

    public function set(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        if (!isset($model->type)) {
            return $value;
        }

        return self::format($value, $model->type);
    }

    public static function format(mixed $value, ?Type $type)
    {
        if (is_null($value)) {
            return null;
        }
        if (is_null($type)) {
            return $value;
        }

        return $type->castValue($value);
    }
}
