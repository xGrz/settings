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
            self::CREATE => self::getLabel('cyan'),
            self::UPDATE => self::getLabel('green'),
            self::DELETE => self::getLabel('yellow'),
            self::UNCHANGED => self::getLabel('gray'),
            self::FORCE_UPDATE => self::getLabel('red'),
        };
    }

    public function getLabel(?string $color = null): string
    {
        if (! $color) {
            return $this->name;
        }

        return "<fg=$color>$this->name</>";
    }
}
