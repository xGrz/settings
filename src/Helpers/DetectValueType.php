<?php

namespace XGrz\Settings\Helpers;

use XGrz\Settings\Enums\Type;
use XGrz\Settings\Exceptions\UnresolvableValueTypeException;

class DetectValueType
{
    /**
     * @throws UnresolvableValueTypeException
     */
    public static function make(mixed $value): Type
    {
        if (is_bool($value)) {
            return Type::YES_NO;
        }
        if (is_float($value)) {
            return Type::FLOAT;
        }
        if (is_int($value)) {
            return Type::INTEGER;
        }
        if (is_string($value)) {
            return str($value)->length() > 200 ? Type::TEXT : Type::STRING;
        }

        if (is_object($value)) {
            throw new UnresolvableValueTypeException('Could not detect setting type by its value [ object given ]');
        }

        throw new UnresolvableValueTypeException('Could not detect setting type by its value [null]');
    }
}