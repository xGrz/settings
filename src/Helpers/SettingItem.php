<?php

namespace XGrz\Settings\Helpers;

use XGrz\Settings\Enums\Type;
use XGrz\Settings\Models\Setting;

class SettingItem
{
    readonly public int $id;
    readonly public string $key;
    public mixed $value;
    public Type $type;

    public static function make(Setting $setting): self
    {
        return new self($setting);
    }

    private function __construct(Setting $setting)
    {
        $this->id = $setting->id;
        $this->key = $setting->key;
        $this->value = $setting->value;
        $this->type = $setting->type;
    }

    public function __invoke()
    {
        return $this->value;
    }
}