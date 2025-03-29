<?php

namespace XGrz\Settings\Tests\Unit;

use Illuminate\Support\Str;
use XGrz\Settings\Enums\SettingType;
use XGrz\Settings\Exceptions\DetectValueTypeException;
use XGrz\Settings\Helpers\SettingEntry;
use XGrz\Settings\Tests\TestCase;

class SettingEntryTest extends TestCase
{
    public SettingEntry $entry;

    protected function setUp(): void
    {
        parent::setUp();
        $this->entry = SettingEntry::make(SettingType::YES_NO, 'System Settings', 'app name', 'Application name', 'Laravel Settings', '\\App\\Console\\');
    }

    public function test_definition_has_prefix()
    {
        $this->assertArrayHasKey('prefix', $this->entry->getDefinition());
    }

    public function test_definition_has_suffix()
    {
        $this->assertArrayHasKey('suffix', $this->entry->getDefinition());
    }

    public function test_definition_has_setting_type()
    {
        $this->assertArrayHasKey('setting_type', $this->entry->getDefinition());
    }

    public function test_definition_has_value()
    {
        $this->assertArrayHasKey('value', $this->entry->getDefinition());
    }

    public function test_definition_has_description()
    {
        $this->assertArrayHasKey('description', $this->entry->getDefinition());
    }

    public function test_definition_has_context()
    {
        $this->assertArrayHasKey('context', $this->entry->getDefinition());
    }

    public function test_definition_array_returns_all_props()
    {
        $this->assertCount(6, $this->entry->getDefinition());
    }

    public function test_setting_entry_converts_prefix_to_camel_case()
    {
        $this->assertEquals('systemSettings', $this->entry->getDefinition()['prefix']);
    }

    public function test_setting_entry_converts_suffix_to_camel_case()
    {
        $this->assertEquals('appName', $this->entry->getDefinition()['suffix']);
    }


    public function test_detect_setting_type_null_throws_exception()
    {
        $this->expectException(DetectValueTypeException::class);
        $this->expectExceptionMessage('Could not detect setting type by its value [null]');
        SettingEntry::detectSettingType(null);
    }

    public function test_detect_setting_type_boolean_on_off()
    {
        $this->assertEquals(SettingType::ON_OFF, SettingEntry::detectSettingType(true));
        $this->assertEquals(SettingType::ON_OFF, SettingEntry::detectSettingType(false));
        $this->assertNotEquals(SettingType::ON_OFF, SettingEntry::detectSettingType(1));
        $this->assertNotEquals(SettingType::ON_OFF, SettingEntry::detectSettingType(0));
    }

    public function test_detect_setting_type_integer()
    {
        $this->assertEquals(SettingType::INTEGER, SettingEntry::detectSettingType(123));
        $this->assertNotEquals(SettingType::INTEGER, SettingEntry::detectSettingType(123.2));
        $this->assertNotEquals(SettingType::INTEGER, SettingEntry::detectSettingType('1233'));
        $this->assertNotEquals(SettingType::INTEGER, SettingEntry::detectSettingType('1233.5'));
    }

    public function test_detect_setting_type_float()
    {
        $this->assertEquals(SettingType::FLOAT, SettingEntry::detectSettingType(123.2));
        $this->assertNotEquals(SettingType::FLOAT, SettingEntry::detectSettingType('1233.2'));
        $this->assertNotEquals(SettingType::FLOAT, SettingEntry::detectSettingType(123));
    }

    public function test_detect_setting_type_string()
    {
        $this->assertEquals(SettingType::STRING, SettingEntry::detectSettingType('string'));
        $this->assertEquals(SettingType::STRING, SettingEntry::detectSettingType(' 123'));
    }

    public function test_detect_setting_type_text()
    {
        $this->assertEquals(SettingType::TEXT, SettingEntry::detectSettingType(Str::random(201)));
        $this->assertNotEquals(SettingType::TEXT, SettingEntry::detectSettingType(Str::random(200)));
    }

}
