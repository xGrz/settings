<?php

namespace XGrz\Settings\Helpers;

use XGrz\Settings\Enums\Type;

class CastValueToType
{
    public static function make(mixed $value, ?Type $type): mixed
    {
        if (is_null($type)) {
            return $value;
        }

        return $type->castValue($value);
    }
}
