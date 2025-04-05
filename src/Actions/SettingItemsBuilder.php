<?php

namespace XGrz\Settings\Actions;

use Illuminate\Support\Collection;
use XGrz\Settings\Models\Setting;
use XGrz\Settings\ValueObjects\Entry;
use XGrz\Settings\ValueObjects\SettingItem;

class SettingItemsBuilder
{
    public static function make(Collection $definitions, Collection $stored)
    {
        $definitions->transform(function (Entry $entry) {
            return [
                'definedDescription' => $entry->getDescription(),
                'definedType' => $entry->getType(),
                'definedValue' => $entry->getValue(),
            ];
        });

        $stored->transform(function (Setting $setting) {
            return [
                'storedDescription' => $setting->description,
                'storedType' => $setting->type,
                'storedValue' => $setting->value,
            ];
        });

        return self::mergeSettingItems($definitions->toArray(), $stored->toArray());
    }

    /**
     * @return Collection<string, SettingItem>
     */
    private static function mergeSettingItems(array $definitions, array $stored): Collection
    {
        $settings = [];
        foreach ($definitions as $key => $definition) {
            $settings[$key] = array_key_exists($key, $settings)
                ? array_merge($definition, $settings[$key])
                : $definition;
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
