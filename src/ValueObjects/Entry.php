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
}
