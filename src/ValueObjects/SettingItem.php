<?php

namespace XGrz\Settings\ValueObjects;

use XGrz\Settings\Enums\Operation;
use XGrz\Settings\Enums\Type;
use XGrz\Settings\Exceptions\SettingItemDefinitionException;
use XGrz\Settings\Helpers\Config\SettingsConfig;
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
        $this->definedType = $setting['definedType'] ?? NULL;
        $this->storedType = $setting['storedType'] ?? NULL;
        $this->definedValue = $setting['definedValue'] ?? NULL;
        $this->storedValue = $setting['storedValue'] ?? NULL;
        $this->definedDescription = $setting['definedDescription'] ?? NULL;
        $this->storedDescription = $setting['storedDescription'] ?? NULL;

        $this->operation = $this->detectOperationType();
    }

    private function detectOperationType(): Operation
    {
        if (isset($this->definedType) && isset($this->storedType) && $this->storedType === $this->definedType and $this->storedDescription === $this->definedDescription) {
            return Operation::UNCHANGED;
        }

        if (! isset($this->definedType) && ! isset($this->storedType)) {
            throw new SettingItemDefinitionException('Types (stored and defined) are not set.');
        }

        if (! isset($this->storedType)) {
            return Operation::CREATE;
        }

        if (! isset($this->definedType)) {
            return Operation::DELETE;
        }

        if ($this->storedType !== $this->definedType && ! $this->storedType->canBeChangedTo($this->definedType)) {
            return Operation::FORCE_UPDATE;
        }

        return Operation::UPDATE;
    }

    public function getOperationType(): Operation
    {
        return $this->operation;
    }

    public function performOperation(): bool
    {
        return match ($this->getOperationType()) {
            Operation::CREATE => $this->performOperationCreate(),
            Operation::UPDATE => $this->performOperationUpdate(),
            Operation::FORCE_UPDATE => $this->performOperationForceUpdate(),
            Operation::DELETE => $this->performOperationDelete(),
            Operation::UNCHANGED => false,
        };
    }

    private function performOperationCreate(): bool
    {
        Setting::create([
            'key' => $this->key,
            'type' => $this->definedType,
            'value' => $this->definedValue,
            'description' => $this->definedDescription,
        ]);

        return true;
    }

    private function performOperationUpdate(): bool
    {
        Setting::where('key', $this->key)
            ->first()
            ->update([
                'type' => $this->definedType,
                'description' => $this->definedDescription,
            ]);

        return true;
    }

    private function performOperationForceUpdate(): bool
    {
        $setting = Setting::where('key', $this->key)->firstOrFail();
        $setting->update([
            'type' => $this->definedType,
            'value' => $setting->value,
            'description' => $this->definedDescription,
        ]);

        return true;
    }

    private function performOperationDelete(): bool
    {
        Setting::where('key', $this->key)
            ->first()
            ->delete();

        return true;
    }

}
