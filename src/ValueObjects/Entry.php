<?php

namespace XGrz\Settings\ValueObjects;

use XGrz\Settings\Casts\DynamicSettingValueCast;
use XGrz\Settings\Enums\Type;
use XGrz\Settings\Exceptions\UnresolvableValueTypeException;

class Entry
{
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

    /**
     * @throws UnresolvableValueTypeException
     */
    public function toArray(): array
    {
        $definition = [
            'description' => $this->getDescription(),
            'type' => $this->getType(),
        ];

        $definition['value'] = DynamicSettingValueCast::format($this->value, $this->type);

        return $definition;
    }

    /**
     * @throws UnresolvableValueTypeException
     */
    private function detectTypeFromValue(): Type
    {
        if (gettype($this->value) === 'boolean') {
            return Type::YES_NO;
        }
        if (is_float($this->value)) {
            return Type::FLOAT;
        }
        if (is_int($this->value)) {
            return Type::INTEGER;
        }
        if (is_string($this->value)) {
            return str($this->value)->length() > 200 ? Type::TEXT : Type::STRING;
        }

        throw new UnresolvableValueTypeException('Could not detect setting type by its value [' . is_null($this->value) ? 'null' : $this->value . ']');
    }

    /**
     * @throws UnresolvableValueTypeException
     */
    public function getType(): ?Type
    {
        return !is_null($this->type) ? $this->type : self::detectTypeFromValue();
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getValue(): float|int|string|null
    {
        return DynamicSettingValueCast::format($this->getValue(), $this->getType());
    }
}
