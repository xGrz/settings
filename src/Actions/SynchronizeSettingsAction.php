<?php

namespace XGrz\Settings\Actions;

use Illuminate\Support\Collection;
use XGrz\Settings\Enums\Operation;
use XGrz\Settings\Interfaces\SettingActionInterface;

class SynchronizeSettingsAction extends BaseSettingAction implements SettingActionInterface
{
    protected ?Operation $operation = NULL;

    protected function getSelectedSettings(): Collection
    {
        return $this->settings->filter(fn($setting) => $setting->operation !== Operation::UNCHANGED);
    }
}
