<?php

namespace XGrz\Settings\Actions;

use XGrz\Settings\Enums\Operation;
use XGrz\Settings\Interfaces\SettingActionInterface;

class CreateSettingsAction extends BaseSettingAction implements SettingActionInterface
{
    protected ?Operation $operation = Operation::CREATE;
}
