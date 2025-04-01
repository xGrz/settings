<?php

namespace XGrz\Settings\Enums;

enum KeyNaming: string
{
    case CAMEL_CASE = 'camelCase';
    case SNAKE_CASE = 'snake_case';
    case KEBAB_CASE = 'kebab-case';

    public function generateKey(string $keyName): string
    {
        return match ($this) {
            self::CAMEL_CASE => $this->generateCamelCase($keyName),
            self::SNAKE_CASE => $this->generateSnakeCase($keyName),
            self::KEBAB_CASE => $this->generateKebabCase($keyName),
        };
    }

    private function generateCamelCase(string $keyName): string
    {
        return str($keyName)
            ->replaceMatches('/[^\w\s\-]/u', '')
            ->camel()
            ->toString();
    }

    private function generateSnakeCase(string $keyName): string
    {
        return str($keyName)
            ->replaceMatches('/[^\w\s\-]/u', '')
            ->snake()
            ->toString();
    }

    private function generateKebabCase(string $keyName): string
    {
        return str($keyName)
            ->replaceMatches('/[^\w\s\-]/u', '')
            ->kebab();
    }
}
