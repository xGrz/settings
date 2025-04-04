<?php

namespace XGrz\Settings\Enums;

enum Operation: string
{
    case CREATE = 'create';
    case UPDATE = 'update';
    case FORCE_UPDATE = 'force_update';
    case DELETE = 'delete';
    case UNCHANGED = 'unchanged';

    public function commandLineLabel(): string
    {
        return match ($this) {
            self::CREATE => self::getLabel('green'),
            self::UPDATE => self::getLabel('yellow'),
            self::DELETE => self::getLabel('red'),
            self::UNCHANGED => self::getLabel('gray'),
        };
    }

    public function getLabel(?string $color = null): string
    {
        if (!$color) {
            return $this->name;
        }

        return "<fg=$color>$this->name</>";
    }
}
