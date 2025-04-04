<?php

namespace XGrz\Settings\ValueObjects;

use XGrz\Settings\Casts\DynamicSettingValueCast;
use XGrz\Settings\Enums\Type;
use XGrz\Settings\Exceptions\UnresolvableValueTypeException;
use XGrz\Settings\Helpers\DetectValueType;

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
    public function getType(): ?Type
    {
        if (!$this->type instanceof Type) {
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

        return DynamicSettingValueCast::format($this->value, $type);
    }
}
