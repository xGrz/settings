<?php

namespace XGrz\Settings\Helpers\Builder;

use Illuminate\Support\Collection;
use XGrz\Settings\Models\Setting;
use XGrz\Settings\ValueObjects\Entry;
use XGrz\Settings\ValueObjects\SettingItem;

class SettingItemsBuilder
{
    /**
     * @param Collection<string, Entry> $definitions
     * @param Collection<string, Setting> $stored
     * @return Collection<string, SettingItem>
     */
    public static function make(Collection $definitions, Collection $stored): Collection
    {
        $definitionItems = $definitions
            ->map(function (Entry $entry) {
                return [
                    'definedDescription' => $entry->getDescription(),
                    'definedType' => $entry->getType(),
                    'definedValue' => $entry->getValue(),
                ];
            })->toArray();

        $storedItems = $stored
            ->map(function (Setting $setting) {
                return [
                    'storedDescription' => $setting->description,
                    'storedType' => $setting->type,
                    'storedValue' => $setting->value,
                ];
            })->toArray();

        return self::mergeSettingItems($definitionItems, $storedItems);
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
}
