<?php

namespace xGrz\Settings\Observers;

use xGrz\Settings\Models\Setting;

class SettingObserver
{
    public function creating(Setting $setting): void
    {
        $this->formatKeys($setting);
    }

    public function updating(Setting $setting): void
    {
        $this->formatKeys($setting);
        $this->discardIncorrectTypeChanges($setting);
    }

    public function saving(Setting $setting): void
    {
        $this->formatKeys($setting);
    }

    private function formatKeys(Setting $setting): void
    {
        if ($setting->isDirty('prefix')) $setting->prefix = str($setting->prefix)->camel()->toString();
        if ($setting->isDirty('suffix')) $setting->suffix = str($setting->suffix)->camel()->toString();
    }

    private function discardIncorrectTypeChanges(Setting $setting): void
    {
        if ($setting->isClean('setting_type')) return;

        $new = $setting->setting_type;

        if (!$setting->getOriginal('setting_type')->canBeChangedTo($new)) {
            // if change is not allowed revert change;
            $setting->setting_type = $setting->getOriginal('setting_type');
        }
    }
}
