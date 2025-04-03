<?php

namespace XGrz\Settings\Enums;

enum KeyNaming: string
{
    case CAMEL_CASE = 'camelCase';
    case SNAKE_CASE = 'snake_case';
    case KEBAB_CASE = 'kebab-case';

    public function generateKey(string|array $keyName): string
    {
        $keyNames = self::explodeKeyName($keyName);
        foreach ($keyNames as $index => $name) {
            $keyNames[$index] = self::formatKey($name);
        }

        return trim(implode('.', $keyNames));
    }

    private static function explodeKeyName(array|string $keyName): array
    {
        $partials = [];
        if (is_string($keyName)) {
            $keyName = [$keyName];
        }
        foreach ($keyName as $partial) {
            foreach (explode('.', $partial) as $part) {
                $partials[] = $part;
            }
        }

        return $partials;
    }

    private function formatKey(string|array $keyName): string
    {
        $keyName = str($keyName)
            ->replaceMatches('/[^a-zA-Z0-9]/u', ' ')
            ->replaceMatches('/\s+/', ' ')
            ->trim();

        return match ($this) {
            self::CAMEL_CASE => $keyName->camel(),
            self::SNAKE_CASE => $keyName->snake(),
            self::KEBAB_CASE => $keyName->kebab(),
        };
    }
}
