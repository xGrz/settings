<?php

namespace xGrz\Settings\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use xGrz\Settings\Casts\DynamicSettingValueCast;
use xGrz\Settings\Casts\KeyNameCast;
use xGrz\Settings\Casts\SettingTypeCast;
use xGrz\Settings\Database\Factories\SettingFactory;
use xGrz\Settings\Enums\SettingType;
use xGrz\Settings\Helpers\SettingsConfig;

/**
 * @property-read int $id
 * @property string $prefix
 * @property string $suffix
 * @property ?string $description
 * @property ?string $context
 * @property-read string $key
 * @property SettingType $setting_type
 */
class Setting extends Model
{
    use HasFactory;

    protected static function newFactory(): SettingFactory
    {
        return SettingFactory::new();
    }


    protected $casts = [
        'prefix' => KeyNameCast::class,
        'suffix' => KeyNameCast::class,
        'description' => 'string',
        'context' => 'string',
        'setting_type' => SettingTypeCast::class,
        'value' => DynamicSettingValueCast::class
    ];

    protected $guarded = ['id', 'key'];

    public function getTable(): string
    {
        return SettingsConfig::getDatabaseTableName();
    }

}
