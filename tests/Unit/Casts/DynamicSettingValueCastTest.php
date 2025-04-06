<?php

namespace XGrz\Settings\Tests\Unit\Casts;

use PHPUnit\Framework\TestCase;
use XGrz\Settings\Casts\DynamicSettingValueCast;
use XGrz\Settings\Enums\Type;
use XGrz\Settings\Helpers\CastValueToType;
use XGrz\Settings\Models\Setting;

class DynamicSettingValueCastTest extends TestCase
{
    public function test_assigning_integer_value_to_model_is_casted_correctly()
    {
        $setting = new Setting;
        $setting->type = Type::INTEGER;
        $setting->value = '123';

        $this->assertSame(123, $setting->value);
        $this->assertIsInt($setting->value);
    }

    public function test_assigning_float_value_to_model_is_casted_correctly()
    {
        $setting = new Setting;
        $setting->type = Type::FLOAT;
        $setting->value = '123.12';

        $this->assertSame(123.12, $setting->value);
        $this->assertIsFloat($setting->value);
    }

    public function test_assigning_bool_value_yes_no_to_model_is_casted_correctly()
    {
        $setting = new Setting;
        $setting->type = Type::YES_NO;

        $setting->value = '1';
        $this->assertIsBool($setting->value);

        $setting->value = '0';
        $this->assertIsBool($setting->value);
    }

    public function test_assigning_bool_value_on_off_to_model_is_casted_correctly()
    {
        $setting = new Setting;
        $setting->type = Type::ON_OFF;

        $setting->value = '1';
        $this->assertIsBool($setting->value);

        $setting->value = '0';
        $this->assertIsBool($setting->value);
    }

    public function test_set_dynamic_cast_value()
    {
        $setting = new Setting;
        $cast = new DynamicSettingValueCast;

        $setting->type = Type::FLOAT;
        $casted = $cast->set($setting, 'value', '123', []);
        $this->assertSame(123.0, $casted);
    }

    public function test_get_dynamic_cast_value()
    {
        $setting = new Setting;
        $cast = new DynamicSettingValueCast;

        $setting->type = Type::FLOAT;
        $casted = $cast->get($setting, 'value', '123', []);
        $this->assertSame(123.0, $casted);
    }

    public function test_format_text_type()
    {
        $this->assertSame('123', CastValueToType::make(123, Type::TEXT));
    }

    public function test_format_string_type()
    {
        $this->assertSame('123', CastValueToType::make(123, Type::STRING));
    }

    public function test_format_integer_type()
    {
        $this->assertSame(123, CastValueToType::make('123', Type::INTEGER));
    }

    public function test_format_float_type()
    {
        $this->assertSame(123.1, CastValueToType::make('123.1', Type::FLOAT));
    }

    public function test_format_yes_no_type()
    {
        $this->assertSame(true, CastValueToType::make('1', Type::YES_NO));
        $this->assertSame(false, CastValueToType::make('0', Type::YES_NO));
    }

    public function test_format_on_offs_type()
    {
        $this->assertSame(true, CastValueToType::make('1', Type::ON_OFF));
        $this->assertSame(false, CastValueToType::make('0', Type::ON_OFF));
    }

    public function test_format_unchanged_when_type_is_null()
    {
        $this->assertSame(123, CastValueToType::make(123, null));
    }

    public function test_format_unchanged_when_value_is_null()
    {
        $this->assertNull(CastValueToType::make(null, Type::ON_OFF));
    }

    public function test_cast_returns_original_value_when_model_type_missing()
    {
        $setting = new Setting;
        $setting->value = '123';

        $this->assertSame('123', $setting->value);
    }
}
