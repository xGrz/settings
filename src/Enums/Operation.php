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
            self::CREATE => $this->getLabel('cyan'),
            self::UPDATE => $this->getLabel('green'),
            self::DELETE => $this->getLabel('yellow'),
            self::UNCHANGED => $this->getLabel('gray'),
            self::FORCE_UPDATE => $this->getLabel('red'),
        };
    }

    private function getLabel(?string $color = NULL): string
    {
        if (! $color) {
            return $this->name;
        }

        return "<fg=$color>$this->name</>";
    }
}
