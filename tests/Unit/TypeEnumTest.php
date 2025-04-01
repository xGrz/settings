<?php

namespace XGrz\Settings\Tests\Unit;

use XGrz\Settings\Enums\Type;
use XGrz\Settings\Tests\TestCase;

class TypeEnumTest extends TestCase
{

    public function test_setting_type_text_returns_localized_label()
    {
        $type = Type::TEXT;
        $this->assertSame(__('settings::types.text'), $type->getLabel());
    }

    public function test_setting_type_string_returns_localized_label()
    {
        $type = Type::STRING;
        $this->assertSame(__('settings::types.string'), $type->getLabel());
    }

    public function test_setting_type_integer_returns_localized_label()
    {
        $type = Type::INTEGER;
        $this->assertSame(__('settings::types.integer'), $type->getLabel());
    }

    public function test_setting_type_float_returns_localized_label()
    {
        $type = Type::FLOAT;
        $this->assertSame(__('settings::types.float'), $type->getLabel());
    }

    public function test_setting_type_yes_no_returns_localized_label()
    {
        $type = Type::YES_NO;
        $this->assertSame(__('settings::types.yes_no'), $type->getLabel());
    }

    public function test_setting_type_on_off_returns_localized_label()
    {
        $type = Type::ON_OFF;
        $this->app->setLocale('en');
        $this->assertSame('Boolean (On/Off)', $type->getLabel());

        $this->app->setLocale('pl');
        $this->assertSame('Wartość logiczna (włączony/wyłączony)', $type->getLabel());
    }

    public function test_is_boolean_on_type_enum()
    {
        $this->assertTrue(Type::ON_OFF->isBoolean());
        $this->assertTrue(Type::YES_NO->isBoolean());
        $this->assertFalse(Type::STRING->isBoolean());
        $this->assertFalse(Type::TEXT->isBoolean());
        $this->assertFalse(Type::INTEGER->isBoolean());
        $this->assertFalse(Type::FLOAT->isBoolean());
    }


}