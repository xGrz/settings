<?php

namespace XGrz\Settings\Models;

use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use XGrz\Settings\Casts\DynamicSettingValueCast;
use XGrz\Settings\Casts\KeyNameCast;
use XGrz\Settings\Casts\SettingTypeCast;
use XGrz\Settings\Database\Factories\SettingFactory;
use XGrz\Settings\Enums\SettingType;
use XGrz\Settings\Helpers\SettingsConfig;
use XGrz\Settings\Observers\SettingObserver;

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
#[ObservedBy(SettingObserver::class)]
class Setting extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'key'];

    protected $casts = [
        'prefix' => KeyNameCast::class,
        'suffix' => KeyNameCast::class,
        'description' => 'string',
        'context' => 'string',
        'setting_type' => SettingTypeCast::class,
        'value' => DynamicSettingValueCast::class
    ];

    protected static function newFactory(): SettingFactory
    {
        return SettingFactory::new();
    }

    public function getTable(): string
    {
        return SettingsConfig::getDatabaseTableName();
    }

    public function getLabel(): mixed
    {
        return match ($this->setting_type) {
            SettingType::YES_NO => $this->value ? __('settings::label.yes') : __('settings::label.no'),
            SettingType::ON_OFF => $this->value ? __('settings::label.on') : __('settings::label.off'),
            default => $this->value,
        };
    }

}
