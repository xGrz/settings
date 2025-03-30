<?php

namespace XGrz\Settings\Helpers;

use XGrz\Settings\Enums\SettingType;
use XGrz\Settings\Exceptions\DetectValueTypeException;

class SettingEntry
{
    private string $prefix = 'global';
    private ?string $suffix = null;
    private ?SettingType $settingType = null;
    private ?string $description = null;
    private int|float|string|null $value = null;
    private ?string $context = null;


    private function __construct(?SettingType $settingType = null, ?string $prefix = null, ?string $suffix = null, ?string $description = null, int|float|string|bool|null $value = null, ?string $context = null)
    {
        $this
            ->settingType($settingType)
            ->prefix($prefix)
            ->suffix($suffix)
            ->description($description)
            ->value($value)
            ->context($context);
    }

    public static function make(?SettingType $settingType = null, ?string $prefix = null, ?string $suffix = null, ?string $description = null, int|float|string|bool|null $value = null, ?string $context = null): SettingEntry
    {
        return new self($settingType, $prefix, $suffix, $description, $value, $context);
    }

    public function fill(array $data): static
    {
        if (array_key_exists('prefix', $data)) $this->prefix($data['prefix']);
        if (array_key_exists('suffix', $data)) $this->suffix($data['suffix']);
        if (array_key_exists('description', $data)) $this->description($data['description']);
        if (array_key_exists('value', $data)) $this->value($data['value']);
        if (array_key_exists('context', $data)) $this->context($data['context']);
        if (array_key_exists('settingType', $data)) $this->settingType($data['suffix']);
        return $this;
    }

    public function prefix(?string $prefix): static
    {
        $this->prefix = str($prefix)->camel()->toString();

        return $this;
    }

    public function suffix(?string $suffix): static
    {
        if (!empty($this->suffix)) return $this;

        $this->suffix = str($suffix)->camel()->toString();

        return $this;
    }

    public function settingType(?SettingType $settingType): static
    {
        if (!empty($this->settingType)) return $this;

        $this->settingType = $settingType;

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

    public function context(?string $context): static
    {
        if (!empty($this->context)) return $this;

        $this->context = $context;

        return $this;
    }

    /**
     * @throws DetectValueTypeException
     */
    public function getDefinition(): array
    {
        return [
            'prefix' => $this->prefix,
            'suffix' => $this->suffix,
            'description' => $this->description,
            'value' => $this->value,
            'setting_type' => $this->settingType ?? self::detectSettingType($this->value, $this->prefix, $this->suffix),
            'context' => $this->context,
        ];
    }

    /**
     * @throws DetectValueTypeException
     */
    public static function detectSettingType(mixed $value, ?string $prefix = null, ?string $suffix = null): SettingType
    {
        if (gettype($value) === 'boolean') return SettingType::ON_OFF;
        if (is_float($value)) return SettingType::FLOAT;
        if (is_int($value)) return SettingType::INTEGER;
        if (is_string($value)) return str($value)->length() > 200 ? SettingType::TEXT : SettingType::STRING;

        // unknown type
        if (is_null($value)) $value = 'null';
        $message = str('Could not detect setting type by its value [' . $value . ']')
            ->when($prefix && $suffix, fn($message) => $message->append(' for [')->append(join('.', [$prefix, $suffix]))->append(']'));

        throw new DetectValueTypeException($message);
    }
}
