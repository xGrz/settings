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
        $this->detectOperationType();
    }

    private function detectOperationType(): void
    {
        if (isset($this->definedType) && isset($this->storedType) && $this->storedType === $this->definedType) {
            $this->operation = Operation::UNCHANGED;
            return;
        }

        if (isset($this->definedType) && !isset($this->storedType)) {
            $this->operation = Operation::CREATE;
            return;
        }

        if (!isset($this->definedType) && isset($this->storedType)) {
            $this->operation = Operation::DELETE;
            return;
        }

        if ($this->storedType->canBeChangedTo($this->definedType)) {
            $this->operation = Operation::UPDATE;
            return;
        }


        $this->operation = Operation::FORCE_UPDATE;
    }


    public function getOperationType(): Operation
    {
        return $this->operation;
    }

    public function create(): bool
    {
        if ($this->getOperationType() !== Operation::CREATE) return false;
        Setting::create([
            'key' => $this->key,
            'type' => $this->definedType,
            'value' => $this->definedValue,
            'description' => $this->definedDescription,
        ]);
        return true;
    }

    public function update(): bool
    {
        if ($this->getOperationType() !== Operation::UPDATE) return false;
        Setting::where('key', $this->key)
            ->first()
            ->update([
                'type' => $this->definedType,
            ]);

        return true;
    }

    public function forceUpdate(): bool
    {
        if ($this->getOperationType() !== Operation::FORCE_UPDATE) return false;
        Setting::where('key', $this->key)
            ->first()
            ->update([
                'type' => $this->definedType,
            ]);
        return true;
    }

    public function delete(): bool
    {
        if ($this->getOperationType() !== Operation::DELETE) return false;
        Setting::where('key', $this->key)
            ->first()
            ->delete();
        return true;
    }

    public function sync(bool $forced = false): void
    {
        $this->create();
        $this->update();
        if ($forced) $this->forceUpdate();
        $this->delete();

    }
}
