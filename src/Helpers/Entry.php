<?php

namespace XGrz\Settings\Helpers;

use XGrz\Settings\Enums\Type;
use XGrz\Settings\Exceptions\DetectValueTypeException;

class Entry
{
    private array $key = [];
    private ?Type $type = null;
    private ?string $description = null;
    private int|float|string|null $value = null;

    private function __construct(?Type $type = null, ?string $description = null, int|float|string|bool|null $value = null)
    {
        $this
            ->settingType($type)
            ->description($description)
            ->value($value);
    }

    public static function make(?Type $type = null, ?string $description = null, int|float|string|bool|null $value = null): Entry
    {
        return new self($type, $description, $value);
    }

    public function fill(array $data): static
    {
        if (array_key_exists('description', $data)) $this->description($data['description']);
        if (array_key_exists('value', $data)) $this->value($data['value']);
        if (array_key_exists('settingType', $data)) $this->settingType($data['suffix']);
        return $this;
    }

    public function settingType(?Type $settingType): static
    {
        if (!empty($this->type)) return $this;

        $this->type = $settingType;

        return $this;
    }

    public function description(?string $description): static
    {
        if (!empty($this->description)) return $this;

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
        return join('.', $this->key);
    }

    /**
     * @throws DetectValueTypeException
     */
    public function getDefinition(): array
    {
        return [
            'key' => $this->getKey(),
            'description' => $this->description,
            'value' => $this->value,
            'type' => $this->type ?? self::detectSettingType($this->value, $this->getKey()),
        ];
    }

    /**
     * @throws DetectValueTypeException
     */
    public static function detectSettingType(mixed $value, string $keyName): Type
    {
        if (gettype($value) === 'boolean') return Type::ON_OFF;
        if (is_float($value)) return Type::FLOAT;
        if (is_int($value)) return Type::INTEGER;
        if (is_string($value)) return str($value)->length() > 200 ? Type::TEXT : Type::STRING;

        // unknown type
        if (is_null($value)) $value = 'null';
        $message = str('Could not detect setting type by its value [' . $value . ']')
            ->when($keyName, fn($message) => $message->append(' for [')->append($keyName)->append(']'));

        throw new DetectValueTypeException($message);
    }
}
