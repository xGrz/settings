<?php

namespace XGrz\Settings\Helpers;

use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use XGrz\Settings\Models\Setting;
use XGrz\Settings\ValueObjects\Entry;
use XGrz\Settings\ValueObjects\SettingItem;

class DefinitionsHelper
{
    /**
     * @var Collection<string, Entry>
     */
    private readonly Collection $definedSettings;

    /**
     * @var Collection<string, Setting>
     */
    private readonly Collection $storedSettings;

    private readonly Collection $settings;

    /**
     * @throws Exception
     */
    public function __construct(array $definitions = [])
    {
        if (empty($definitions)) {
            $definitions = SettingsConfig::getRawSettingsDefinition();
        }
        $this->definedSettings = collect(Arr::dot($definitions))
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
        $definitions = collect($this->definedSettings->map(fn(Entry $entry) => [
            'definedDescription' => $entry->getDescription(),
            'definedType' => $entry->getType(),
            'definedValue' => $entry->getValue(),
        ]));

        // prepare stored settings
        $stored = $this->storedSettings->map(fn(Setting $setting) => [
            'storedDescription' => $setting->description,
            'storedType' => $setting->type,
            'storedValue' => $setting->value,
        ]);

        // marge stored into definitions
        $settings = $definitions->mapWithKeys(fn($definition, $key) => [
            $key => array_merge($definition, $stored->get($key, [])),
        ]);

        // fill stored not found in definitions
        $stored->each(function ($setting, $key) use ($settings) {
            if (!$settings->has($key)) {
                $settings->put($key, $setting);
            }
        });

        return $settings->transform(fn($setting, $key) => SettingItem::make($setting, $key));
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
            ->filter(fn(SettingItem $setting) => $setting->shouldUpdate())
            ->when($asTableRows, fn(Collection $settings) => $this->formatTableContent($settings));
    }

    public function creatable(bool $asTableRows = false)
    {
        return $this
            ->settings
            ->filter(fn(SettingItem $setting) => $setting->shouldCreate())
            ->when($asTableRows, fn(Collection $settings) => $this->formatTableContent($settings));
    }

    public function deletable(bool $asTableRows = false)
    {
        return $this
            ->settings
            ->filter(fn(SettingItem $setting) => $setting->shouldDelete())
            ->when($asTableRows, fn(Collection $settings) => $this->formatTableContent($settings));
    }

    public function synchronizable(bool $asTableRows = false): Collection
    {
        return $this
            ->settings
            ->filter(fn(SettingItem $setting) => $setting->shouldUpdate() || $setting->shouldCreate() || $setting->shouldDelete())
            ->when($asTableRows, fn(Collection $settings) => $this->formatTableContent($settings));
    }

    public function formatTableContent(Collection $settingItems): Collection
    {
        return $settingItems->map(fn(SettingItem $setting) => [
            'key' => $setting->key,
            'description' => $setting->definedDescription ?? $setting->storedDescription ?? '<fg=gray>Not defined</>',
            'definedType' => $setting->definedType?->name ?? '<fg=gray>---</>',
            'storedType' => $setting->storedType?->name ?? '<fg=gray>---</>',
            'operation' => $setting->operation->commandLineLabel(),
        ]);
    }
}
