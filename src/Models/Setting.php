<?php

namespace xGrz\Settings\Models;

use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use xGrz\Settings\Database\Factories\SettingFactory;
use xGrz\Settings\Enums\SettingType;
use xGrz\Settings\Helpers\SettingsConfig;
use xGrz\Settings\Observers\SettingObserver;

/**
 * @property-read int $id
 * @property string $prefix
 * @property string $suffix
 * @property ?string $description
 * @property ?string $context
 * @property-read string $key
 * @property SettingType $setting_type
 */
#[ObservedBy(SettingObserver::class)]
class Setting extends Model
{
    use HasFactory;

    protected static function newFactory(): SettingFactory
    {
        return SettingFactory::new();
    }

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
