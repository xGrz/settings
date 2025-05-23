<?php

namespace XGrz\Settings\Helpers\Builder;

use Illuminate\Support\Collection;
use XGrz\Settings\Models\Setting;

class GetStoredSettings
{
    private static function getFromDatabase(): Collection
    {
        return Setting::query()
            ->orderBy('key')
            ->get();
    }

    /**
     * @return Collection<string, Setting>
     */
    public static function asCollection(): Collection
    {
        return collect(self::getFromDatabase())
            ->mapWithKeys(function(Setting $setting) {
                return [$setting->key => $setting];
            });
    }
}
