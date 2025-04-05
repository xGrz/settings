<?php

namespace XGrz\Settings\Helpers;

use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use XGrz\Settings\Actions\GetRawDefinitions;
use XGrz\Settings\Enums\Operation;
use XGrz\Settings\Models\Setting;
use XGrz\Settings\ValueObjects\Entry;
use XGrz\Settings\ValueObjects\SettingItem;

class DefinitionsHelper
{
    private Collection $definedSettings;

    private Collection $storedSettings;

    private Collection $settings;

    /**
     * @throws Exception
     */
    public function __construct(array $definitions = [])
    {
        $this->definedSettings = collect(Arr::dot(empty($definitions) ? GetRawDefinitions::make() : $definitions))
            ->filter(fn($value) => $value instanceof Entry)
            ->mapWithKeys(fn(Entry $value, $key) => [SettingsConfig::getKeyGeneratorType()->generateKey($key) => $value])
            ->sortKeys();

        $this->storedSettings = collect(Setting::orderBy('key')->get())
            ->mapWithKeys(function (Setting $setting) {
                return [$setting->key => $setting];
            });

        $this->settings = self::buildSettingItemsList();
    }

    private function buildSettingItemsList(): Collection
    {
        // prepare definitions
        $definitions = collect($this->definedSettings->map(fn(Entry $entry): array => [
            'definedDescription' => $entry->getDescription(),
            'definedType' => $entry->getType(),
            'definedValue' => $entry->getValue(),
        ]));

        // prepare stored settings
        $stored = $this->storedSettings->map(fn(Setting $setting): array => [
            'storedDescription' => $setting->description,
            'storedType' => $setting->type,
            'storedValue' => $setting->value,
        ]);


        // merge stored into definitions
        $settings = [];
        foreach ($definitions as $key => $definition) {
            $settings[$key] = $definition;
        }

        foreach ($stored as $key => $setting) {
            if (array_key_exists($key, $settings)) {
                $settings[$key] = array_merge($setting, $settings[$key]);
            } else {
                $settings[$key] = $setting;
            }
        }


        $output = [];
        foreach ($settings as $key => $definition) {
            $output[$key] = SettingItem::make($definition, $key);
        }

        return collect($output);
    }

    public function toArray(): array
    {
        return $this->settings->toArray();
    }

    public function heading(): array
    {
        return ['Key', 'Description', 'DefinedType', 'StoredType', 'Operation'];
    }

    public function toListing(bool $asTableRows = true): Collection
    {
        return $this
            ->settings
            ->when($asTableRows, fn(Collection $settings) => $this->formatTableContent($settings));
    }

    public function updatable(bool $asTableRows = false): Collection
    {
        return $this
            ->settings
            ->filter(fn(SettingItem $setting) => $setting->operation === Operation::UPDATE)
            ->when($asTableRows, fn(Collection $settings) => $this->formatTableContent($settings));
    }

    public function creatable(bool $asTableRows = false)
    {
        return $this
            ->settings
            ->filter(fn(SettingItem $setting) => $setting->operation === Operation::CREATE)
            ->when($asTableRows, fn(Collection $settings) => $this->formatTableContent($settings));
    }

    public function deletable(bool $asTableRows = false)
    {
        return $this
            ->settings
            ->filter(fn(SettingItem $setting) => $setting->operation === Operation::DELETE)
            ->when($asTableRows, fn(Collection $settings) => $this->formatTableContent($settings));
    }

    public function synchronizable(bool $asTableRows = false): Collection
    {
        return $this
            ->settings
            ->filter(fn(SettingItem $setting) => in_array($setting->operation, [Operation::CREATE, Operation::UPDATE, Operation::DELETE], true))
            ->when($asTableRows, fn(Collection $settings) => $this->formatTableContent($settings));
    }

    public function formatTableContent(Collection $settingItems): Collection
    {
        return $settingItems->map(fn(SettingItem $setting) => [
            'key' => $setting->key,
            'description' => $setting->definedDescription ?? $setting->storedDescription ?? '<fg=gray>Not defined</>',
            'definedType' => $setting->definedType->name ?? '<fg=gray>---</>',
            'storedType' => $setting->storedType->name ?? '<fg=gray>---</>',
            'operation' => $setting->operation->commandLineLabel(),
        ]);
    }
}
