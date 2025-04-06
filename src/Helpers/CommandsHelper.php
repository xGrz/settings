<?php

namespace XGrz\Settings\Helpers;

use Exception;
use Illuminate\Support\Collection;
use XGrz\Settings\Enums\Operation;
use XGrz\Settings\Helpers\Builder\GetSettingsDefinitions;
use XGrz\Settings\Helpers\Builder\GetStoredSettings;
use XGrz\Settings\Helpers\Builder\SettingItemsBuilder;
use XGrz\Settings\ValueObjects\SettingItem;

class CommandsHelper
{
    private Collection $settings;

    /**
     * @throws Exception
     */
    public function __construct(array $definitions = [])
    {
        $this->settings = SettingItemsBuilder::make(
            GetSettingsDefinitions::asCollection($definitions),
            GetStoredSettings::asCollection()
        );
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
