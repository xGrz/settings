<?php

namespace XGrz\Settings\Services;

use XGrz\Settings\Models\Setting;

class SettingsService
{
    private array $settings = [];

    public function __construct()
    {
        $this->settings = Setting::all()->pluck('key', 'value')->toArray();
    }

    public function get(string $key)
    {
        if (!array_key_exists($key, $this->settings)) {
            return null;
        }
        return $this->settings[$key];
    }

}
