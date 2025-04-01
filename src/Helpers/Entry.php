<?php

namespace XGrz\Settings\Helpers;

use XGrz\Settings\Enums\Type;
use XGrz\Settings\Exceptions\UnresolvableValueTypeException;

class Entry
{
    private array $key = [];
    private ?Type $type = null;
    private ?string $description = null;
    private int|float|string|null $value = null;

    private function __construct(int|float|string|bool|null $value = null, ?Type $type = null, ?string $description = null)
    {
        $this
            ->type($type)
            ->description($description)
            ->value($value);
    }

    public static function make(int|float|string|bool|null $value = null, ?Type $type = null, ?string $description = null): Entry
    {
        return new self($value, $type, $description);
    }

    public function appendKey(string $partialKey): static
    {
        $this->key[] = $partialKey;
        return $this;
    }

    public function type(?Type $settingType): static
    {
        $this->type = $settingType;
        return $this;
    }

    public function description(?string $description): static
    {
        $this->description = $description;
        return $this;
    }

    public function value(float|int|string|bool|null $value): static
    {
        $this->value = $value;
        return $this;
    }

    private function getKey(): string
    {
        return SettingsConfig::getKeyGeneratorType()
            ->generateKey($this->key);
    }

    /**
     * @throws UnresolvableValueTypeException
     */
    public function toArray(): array
    {
        return [
            'key' => $this->getKey(),
            'description' => $this->description,
            'value' => $this->value,
            'type' => $this->type ?? self::detectTypeFromValue($this->value, $this->getKey()),
        ];
    }

    /**
     * @throws UnresolvableValueTypeException
     */
    public static function detectTypeFromValue(mixed $value, string $keyName): Type
    {
        if (gettype($value) === 'boolean') return Type::YES_NO;
        if (is_float($value)) return Type::FLOAT;
        if (is_int($value)) return Type::INTEGER;
        if (is_string($value)) return str($value)->length() > 200 ? Type::TEXT : Type::STRING;

        // unknown type
        if (is_null($value)) $value = 'null';
        $message = str('Could not detect setting type by its value [' . $value . ']')
            ->when($keyName, fn($message) => $message->append(' for [')->append($keyName)->append(']'));

        throw new UnresolvableValueTypeException($message);
    }

    /**
     * @throws UnresolvableValueTypeException
     */
    public function detectType(bool $force = false): ?Type
    {
        $type = (!empty($this->type) && !$force)
            ? $this->type
            : self::detectTypeFromValue($this->value, $this->getKey());
        $this->type($type);
        return $this->type;
    }
}
