<?php

use XGrz\Settings\Enums\SettingType;
use XGrz\Settings\Tests\TestCase;

class SettingTypeEnumTest extends TestCase
{

    public function test_setting_type_text_returns_localized_label()
    {
        $type = SettingType::TEXT;
        $this->assertSame(__('settings::types.text'), $type->getLabel());
    }

    public function test_setting_type_string_returns_localized_label()
    {
        $type = SettingType::STRING;
        $this->assertSame(__('settings::types.string'), $type->getLabel());
    }

    public function test_setting_type_integer_returns_localized_label()
    {
        $type = SettingType::INTEGER;
        $this->assertSame(__('settings::types.integer'), $type->getLabel());
    }

    public function test_setting_type_float_returns_localized_label()
    {
        $type = SettingType::FLOAT;
        $this->assertSame(__('settings::types.float'), $type->getLabel());
    }

    public function test_setting_type_yes_no_returns_localized_label()
    {
        $type = SettingType::YES_NO;
        $this->assertSame(__('settings::types.yes_no'), $type->getLabel());
    }

    public function test_setting_type_on_off_returns_localized_label()
    {
        $type = SettingType::ON_OFF;
        $this->app->setLocale('en');
        $this->assertSame('Boolean (On/Off)', $type->getLabel());

        $this->app->setLocale('pl');
        $this->assertSame('Wartość logiczna (włączony/wyłączony)', $type->getLabel());
    }

    public function test_is_boolean_on_type_enum()
    {
        $this->assertTrue(SettingType::ON_OFF->isBoolean());
        $this->assertTrue(SettingType::YES_NO->isBoolean());
        $this->assertFalse(SettingType::STRING->isBoolean());
        $this->assertFalse(SettingType::TEXT->isBoolean());
        $this->assertFalse(SettingType::INTEGER->isBoolean());
        $this->assertFalse(SettingType::FLOAT->isBoolean());
    }


}