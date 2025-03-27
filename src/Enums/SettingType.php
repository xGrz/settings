<?php

namespace xGrz\Settings\Enums;

enum SettingType: int
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
            self::ON_OFF => __('Boolean (ON/OFF)'),
            self::YES_NO => __('Boolean (YES/NO)'),
            self::TEXT => __('Text'),
            self::INTEGER => __('Integer'),
            self::FLOAT => __('Float'),
            self::STRING => __('String'),
        };
    }

}
