<?php

namespace xGrz\Settings\Models;

use Illuminate\Database\Eloquent\Model;
use xGrz\Settings\Enums\SettingType;
use xGrz\Settings\Helpers\SettingsConfig;

class Setting extends Model
{
    protected $casts = [
        'prefix' => 'string',
        'suffix' => 'string',
        'description' => 'string',
        'context' => 'string',
        'setting_type' => SettingType::class,
    ];

    protected $guarded = ['id', 'key'];

    public function getTable(): string
    {
        return SettingsConfig::getDatabaseTableName();
    }

}
