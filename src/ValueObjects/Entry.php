<?php

namespace XGrz\Settings\ValueObjects;

use XGrz\Settings\Casts\DynamicSettingValueCast;
use XGrz\Settings\Enums\Type;
use XGrz\Settings\Exceptions\UnresolvableValueTypeException;

class Entry
{
    private ?Type $type = null;

    private ?string $description = null;

    private bool|int|float|string|null $value = null;

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
        return [
            'description' => $this->getDescription(),
            'type' => $this->getType(),
            'value' => $this->getValue(),
        ];
    }

    /**
     * @throws UnresolvableValueTypeException
     */
    private function detectTypeFromValue(): Type
    {
        if (is_bool($this->value)) {
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

        throw new UnresolvableValueTypeException('Could not detect setting type by its value [null]');
    }

    /**
     * @throws UnresolvableValueTypeException
     */
    public function getType(): ?Type
    {
        if (!$this->type instanceof Type) {
            $this->type = self::detectTypeFromValue();
        }

        return $this->type;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getValue(): float|int|string|null|bool
    {
        $type = $this->getType();

        return DynamicSettingValueCast::format($this->value, $type);
    }
}
