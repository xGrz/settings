<?php

namespace XGrz\Settings\ValueObjects;

use XGrz\Settings\Enums\Operation;
use XGrz\Settings\Enums\Type;
use XGrz\Settings\Helpers\SettingsConfig;
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
        $this->key = SettingsConfig::getKeyGeneratorType()->generateKey($key);
        $this->definedType = $setting['definedType'] ?? null;
        $this->storedType = $setting['storedType'] ?? null;
        $this->definedValue = $setting['definedValue'] ?? null;
        $this->storedValue = $setting['storedValue'] ?? null;
        $this->definedDescription = $setting['definedDescription'] ?? null;
        $this->storedDescription = $setting['storedDescription'] ?? null;

        $this->operation = $this->detectOperationType();
    }

    private function detectOperationType(): Operation
    {
        if (isset($this->definedType) && isset($this->storedType) && $this->storedType === $this->definedType) {
            return Operation::UNCHANGED;
        }

        if (isset($this->definedType) && !isset($this->storedType)) {
            return Operation::CREATE;
        }

        if (!isset($this->definedType) && isset($this->storedType)) {
            return Operation::DELETE;
        }

        if (isset($this->definedType) && isset($this->storedType) && $this->storedType->canBeChangedTo($this->definedType)) {
            return Operation::UPDATE;
        }

        return Operation::FORCE_UPDATE;
    }

    public function getOperationType(): Operation
    {
        return $this->operation;
    }

    public function create(): bool
    {
        if ($this->getOperationType() !== Operation::CREATE) {
            return false;
        }
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
        if ($this->getOperationType() !== Operation::UPDATE) {
            return false;
        }
        Setting::where('key', $this->key)
            ->first()
            ->update([
                'type' => $this->definedType,
            ]);

        return true;
    }

    public function forceUpdate(): bool
    {
        if ($this->getOperationType() !== Operation::FORCE_UPDATE) {
            return false;
        }
        $setting = Setting::where('key', $this->key)->firstOrFail();
        $setting->update([
            'type' => $this->definedType,
            'value' => $setting->value, // todo: force cast value to new type in observer
        ]);

        return true;
    }

    public function delete(): bool
    {
        if ($this->getOperationType() !== Operation::DELETE) {
            return false;
        }
        Setting::where('key', $this->key)
            ->first()
            ->delete();

        return true;
    }

    public function sync(bool $forced = false): bool
    {
        $results[] = $this->create() ? 1 : 0;
        $results[] = $this->update() ? 1 : 0;
        if ($forced) {
            $results[] = $this->forceUpdate() ? 1 : 0;
        }
        $results[] = $this->delete() ? 1 : 0;

        return array_sum($results) > 0;

    }
}
