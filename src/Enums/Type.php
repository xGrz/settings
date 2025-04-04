<?php

namespace XGrz\Settings\Enums;

enum Type: int
{
    case ON_OFF = 1;
    case YES_NO = 2;
    case TEXT = 10;
    case STRING = 11;
    case INTEGER = 20;
    case FLOAT = 21;

    public function getLabel(): string
    {
        return match ($this) {
            self::ON_OFF => __('settings::types.on_off'),
            self::YES_NO => __('settings::types.yes_no'),
            self::TEXT => __('settings::types.text'),
            self::INTEGER => __('settings::types.integer'),
            self::FLOAT => __('settings::types.float'),
            self::STRING => __('settings::types.string'),
        };
    }

    public function allowedChanges(): array
    {
        // First value is old, second value is new (can be changed to)
        return match ($this) {
            self::ON_OFF => [self::YES_NO],
            self::YES_NO => [self::ON_OFF],
            self::INTEGER => [self::FLOAT],
            self::STRING => [self::TEXT],
            default => [],
        };
    }

    public function canBeChangedTo(?Type $settingType): bool
    {
        return in_array($settingType, $this->allowedChanges());
    }

    public function castValue(mixed $value): mixed
    {
        return match ($this) {
            self::ON_OFF, self::YES_NO => (bool)$value,
            self::INTEGER => str($value)->toInteger(),
            self::FLOAT => str($value)->replace(',', '.')->toFloat(),
            self::STRING, self::TEXT => str($value)->toString(),
        };
    }

    public function isBoolean(): bool
    {
        return match ($this) {
            self::ON_OFF, self::YES_NO => true,
            default => false,
        };
    }
}
