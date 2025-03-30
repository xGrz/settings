<?php

namespace XGrz\Settings\Observers;

use XGrz\Settings\Facades\Settings;
use XGrz\Settings\Models\Setting;

class SettingObserver
{

    public function created(Setting $setting): void
    {
        Settings::refreshCache();
    }

    public function saved(Setting $setting): void
    {
        Settings::refreshCache();
    }

    public function updated(Setting $setting): void
    {
        Settings::refreshCache();
    }

    public function deleted(Setting $setting): void
    {
        Settings::refreshCache();
    }
}
