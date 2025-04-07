<?php

namespace XGrz\Settings\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use XGrz\Settings\Casts\DynamicSettingValueCast;
use XGrz\Settings\Casts\KeyNameCast;
use XGrz\Settings\Enums\Type;
use XGrz\Settings\Helpers\SettingsConfig;

/**
 * @property-read int $id
 * @property-read string $key
 * @property ?string $description
 * @property Type $type
 * @property mixed $value
 * @property-read Carbon $created_at
 * @property-read Carbon $updated_at
 */
class Setting extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'key' => KeyNameCast::class,
        'description' => 'string',
        'type' => Type::class,
        'value' => DynamicSettingValueCast::class,
    ];

    public function getTable(): string
    {
        return SettingsConfig::getDatabaseTableName();
    }

    public function getLabel(): mixed
    {
        return match ($this->type) {
            Type::YES_NO => $this->value ? __('settings::label.yes') : __('settings::label.no'),
            Type::ON_OFF => $this->value ? __('settings::label.on') : __('settings::label.off'),
            default => $this->value,
        };
    }

    public function refreshKey(): static
    {
        $this->setAttribute('key', SettingsConfig::getKeyGeneratorType()->generateKey($this->key));
        $this->save();

        return $this;
    }

    public function isBoolean(): bool
    {
        return $this->type->isBoolean();
    }
}
