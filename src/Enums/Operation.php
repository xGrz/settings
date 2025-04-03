<?php

namespace XGrz\Settings\Enums;

enum Operation: string
{
    case CREATE = 'create';
    case UPDATE = 'update';
    case DELETE = 'delete';
    case SKIP = 'skip';

    public function commandLineLabel(): string
    {
        return match ($this) {
            self::CREATE => self::getLabel('green'),
            self::UPDATE => self::getLabel('yellow'),
            self::DELETE => self::getLabel('red'),
            self::SKIP => self::getLabel('cyan'),
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
