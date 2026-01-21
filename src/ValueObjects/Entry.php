<?php

namespace XGrz\Settings\ValueObjects;

use XGrz\Settings\Enums\Type;
use XGrz\Settings\Exceptions\UnresolvableValueTypeException;
use XGrz\Settings\Helpers\Values\CastValueToType;
use XGrz\Settings\Helpers\Values\DetectValueType;

class Entry
{
    private ?Type $type = NULL;

    private ?string $description = NULL;

    private bool|int|float|string|null $value = NULL;

    private function __construct(int|float|string|bool|null $value = NULL, ?Type $type = NULL, ?string $description = NULL)
    {
        $this
            ->type($type)
            ->description($description)
            ->value($value);
    }

    public static function make(int|float|string|bool|null $value = NULL, ?Type $type = NULL, ?string $description = NULL): Entry
    {
        return new self($value, $type, $description);
    }

    /**
     * @deprecated Use `Entry` type helpers instead.
     */
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
    public function getType(): ?Type
    {
        if (! $this->type instanceof Type) {
            $this->type = DetectValueType::make($this->value);
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

        return CastValueToType::make($this->value, $type);
    }

    public function stringValue(): static
    {
        $this->type(Type::STRING);

        return $this;
    }

    public function textValue(): static
    {
        $this->type(Type::TEXT);

        return $this;
    }

    public function floatValue(): static
    {
        $this->type(Type::FLOAT);

        return $this;
    }

    public function integerValue(): static
    {
        $this->type(Type::INTEGER);

        return $this;
    }

    public function onOffValue(): static
    {
        $this->type(Type::ON_OFF);

        return $this;
    }


    public function yesNoValue(): static
    {
        $this->type(Type::YES_NO);

        return $this;
    }

    public function digitsValue(): static
    {
        $this->type(Type::DIGITS);
        
        return $this;
    }

}
