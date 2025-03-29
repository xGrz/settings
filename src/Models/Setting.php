<?php

namespace XGrz\Settings\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use XGrz\Settings\Casts\DynamicSettingValueCast;
use XGrz\Settings\Casts\KeyNameCast;
use XGrz\Settings\Casts\SettingTypeCast;
use XGrz\Settings\Database\Factories\SettingFactory;
use XGrz\Settings\Enums\SettingType;
use XGrz\Settings\Helpers\SettingsConfig;

/**
 * @property-read int $id
 * @property string $prefix
 * @property string $suffix
 * @property ?string $description
 * @property ?string $context
 * @property-read string $key
 * @property SettingType $setting_type
 * @property mixed $value
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
