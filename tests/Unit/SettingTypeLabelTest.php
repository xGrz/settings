<?php

use XGrz\Settings\Enums\SettingType;
use XGrz\Settings\Tests\TestCase;

class SettingTypeLabelTest extends TestCase
{
    public function test_setting_type_text_returns_localized_label()
    {
        $type = SettingType::TEXT;
        $this->assertSame(__('Text'), $type->getLabel());
    }

    public function test_setting_type_string_returns_localized_label()
    {
        $type = SettingType::STRING;
        $this->assertSame(__('String'), $type->getLabel());
    }

    public function test_setting_type_integer_returns_localized_label()
    {
        $type = SettingType::INTEGER;
        $this->assertSame(__('Integer'), $type->getLabel());
    }

    public function test_setting_type_float_returns_localized_label()
    {
        $type = SettingType::FLOAT;
        $this->assertSame(__('Float'), $type->getLabel());
    }

    public function test_setting_type_yes_no_returns_localized_label()
    {
        $type = SettingType::YES_NO;
        $this->assertSame(__('Boolean (YES/NO)'), $type->getLabel());
    }

    public function test_setting_type_on_off_returns_localized_label()
    {
        $type = SettingType::ON_OFF;
        $this->assertSame(__('Boolean (ON/OFF)'), $type->getLabel());
    }


}