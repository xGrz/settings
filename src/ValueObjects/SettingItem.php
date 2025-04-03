<?php

namespace XGrz\Settings\ValueObjects;

use XGrz\Settings\Enums\Operation;
use XGrz\Settings\Enums\Type;
use XGrz\Settings\Models\Setting;

class SettingItem
{
    public readonly string $key;

    public readonly ?Type $definedType;

    public readonly ?Type $storedType;

    public readonly mixed $definedValue;

    public readonly mixed $storedValue;

    public readonly ?string $definedDescription;

    public readonly ?string $storedDescription;

    public readonly Operation $operation;

    public static function make(array $setting, string $key): SettingItem
    {
        return new self($setting, $key);
    }

    private function __construct(array $setting, string $key)
    {
        $this->key = $key;
        $keys = ['definedType', 'storedType', 'definedValue', 'storedValue', 'definedDescription', 'storedDescription'];
        foreach ($keys as $key) {
            if (array_key_exists($key, $setting)) {
                $this->$key = $setting[$key];
            } else {
                $this->$key = null;
            }
        }
        if ($this->shouldCreate()) {
            $this->operation = Operation::CREATE;
        }
        if ($this->shouldUpdate()) {
            $this->operation = Operation::UPDATE;
        }
        if ($this->shouldDelete()) {
            $this->operation = Operation::DELETE;
        }
        if (!isset($this->operation)) {
            $this->operation = Operation::SKIP;
        }
    }

    private function isTypeMatch(): bool
    {
        return $this->definedType !== null
            && $this->storedType !== null
            && $this->definedType === $this->storedType;
    }

    private function canChangeType(): bool
    {
        if (!self::isTypeMatch()) {
            return false;
        }

        return $this->storedType->canBeChangedTo($this->definedType); // todo check for data loss
    }

    public function shouldUpdate(): bool
    {
        return $this->canChangeType();
    }

    public function shouldCreate(): bool
    {
        return is_null($this->storedValue)
            && is_null($this->storedDescription)
            && is_null($this->storedType);
    }

    public function shouldDelete(): bool
    {
        return is_null($this->definedValue)
            && is_null($this->definedDescription)
            && is_null($this->definedType);
    }

    public function update(): void
    {
        Setting::where('key', $this->key)
            ->first()
            ->update([
                'type' => $this->definedType,
            ]);
    }

    public function create(): void
    {
        Setting::create([
            'key' => $this->key,
            'type' => $this->definedType,
            'value' => $this->definedValue,
            'description' => $this->definedDescription,
        ]);
    }

    public function delete(): void
    {
        Setting::where('key', $this->key)
            ->first()
            ->delete();
    }
}
