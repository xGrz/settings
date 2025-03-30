<?php

namespace XGrz\Settings\Observers;

use XGrz\Settings\Facades\Settings;
use XGrz\Settings\Models\Setting;

class SettingObserver
{
    public function saved(Setting $setting): void
    {
        Settings::invalidateCache();
    }

    public function deleted(Setting $setting): void
    {
        Settings::invalidateCache();
    }
}
