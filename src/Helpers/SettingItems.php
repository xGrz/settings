<?php

namespace XGrz\Settings\Helpers;

use Illuminate\Support\Collection;
use XGrz\Settings\Enums\Operation;
use XGrz\Settings\Helpers\Builder\GetSettingsDefinitions;
use XGrz\Settings\Helpers\Builder\GetStoredSettings;
use XGrz\Settings\Models\Setting;
use XGrz\Settings\ValueObjects\Entry;
use XGrz\Settings\ValueObjects\SettingItem;

/**
 * Represents a collection of settings, combining definitions with stored values.
 */
class SettingItems
{
    /**
     * @var Collection<string| SettingItem>
     */
    private Collection $items;

    /**
     * @param ?Collection<string, Entry>   $definitions
     * @param ?Collection<string, Setting> $stored
     */
    public function __construct(?Collection $definitions = NULL, ?Collection $stored = NULL)
    {
        $this->buildSettingsCollection(
            $definitions ?? GetSettingsDefinitions::asCollection(),
            $stored ?? GetStoredSettings::asCollection()
        );
    }

    /**
     * @param Collection<string, Entry>   $definitions
     * @param Collection<string, Setting> $stored
     * @return Collection<string, SettingItem>
     */
    private function buildSettingsCollection(Collection $definitions, Collection $stored): Collection
    {
        $definitionItems = $definitions
            ->map(function(Entry $entry) {
                return [
                    'definedDescription' => $entry->getDescription(),
                    'definedType' => $entry->getType(),
                    'definedValue' => $entry->getValue(),
                ];
            })->toArray();

        $storedItems = $stored
            ->map(function(Setting $setting) {
                return [
                    'storedDescription' => $setting->description,
                    'storedType' => $setting->type,
                    'storedValue' => $setting->value,
                ];
            })->toArray();

        $this->items = self::mergeSettingItems($definitionItems, $storedItems);
        return $this->items;
    }

    /**
     * @return Collection<string, SettingItem>
     */
    private static function mergeSettingItems(array $definitions, array $stored): Collection
    {
        $settings = [];
        foreach ($definitions as $key => $definition) {
            $settings[$key] = $definition;
        }

        foreach ($stored as $key => $setting) {
            $settings[$key] = array_key_exists($key, $settings)
                ? array_merge($setting, $settings[$key])
                : $setting;
        }

        // convert to array od SettingItems
        $output = [];
        foreach ($settings as $key => $definition) {
            $output[$key] = SettingItem::make($definition, $key);
        }

        return collect($output);
    }

    /**
     * @param Operation|array|null $operations
     * @return Collection<string, SettingItem>
     */
    public function getItems(Operation|array|null $operations = NULL): Collection
    {
        if (is_null($operations)) {
            return $this->items;
        }

        if ($operations instanceof Operation) {
            $operations = [$operations];
        }

        return $this
            ->items
            ->filter(fn(SettingItem $item) => in_array($item->getOperationType(), $operations));
    }

    public static function getTableHeading(): array
    {
        return ['Key', 'Description', 'DefinedType', 'StoredType', 'Operation'];
    }

    /**
     * @param Operation|array|null $operations
     * @return Collection
     */
    public function getTableBody(Operation|array|null $operations = NULL): Collection
    {
        return $this->getItems($operations)
            ->map(fn(SettingItem $setting) => [
                'key' => $setting->key,
                'description' => $setting->definedDescription ?? $setting->storedDescription ?? '<fg=gray>Not defined</>',
                'definedType' => $setting->definedType->name ?? '<fg=gray>---</>',
                'storedType' => $setting->storedType->name ?? '<fg=gray>---</>',
                'operation' => $setting->operation->commandLineLabel(),
            ]);
    }


}