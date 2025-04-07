<?php

namespace XGrz\Settings\Observers;

use XGrz\Settings\Facades\Settings;

class SettingObserver
{
    public function created(): void
    {
        Settings::invalidateCache();
    }

    public function updated(): void
    {
        Settings::refreshCache();
    }

    public function saved(): void
    {
        Settings::invalidateCache();
    }

    public function deleted(): void
    {
        Settings::invalidateCache();
    }
}