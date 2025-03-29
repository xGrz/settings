<?php

namespace XGrz\Settings\Tests\Feature;

use XGrz\Settings\Enums\SettingType;
use XGrz\Settings\Helpers\SettingsConfig;
use XGrz\Settings\Models\Setting;
use XGrz\Settings\Tests\TestCase;

class SettingModelTest extends TestCase
{

    private function createSetting(SettingType $settingType): Setting
    {
        return Setting::create([
            'prefix' => 'test' . rand(1,200000),
            'suffix' => str($settingType->name)->lower()->toString(),
            'setting_type' => $settingType,
        ])->refresh();
    }

    public function test_cast_value_to_integer(): void
    {
        $s = self::createSetting(SettingType::INTEGER);

        $s->update(['value' => "124404"]);
        $this->assertEquals(124404, $s->value);
        $this->assertDatabaseHas(SettingsConfig::getDatabaseTableName(), [
            'id' => $s->id,
            'value' => 124404
        ]);

        $s->update(['value' => "124405.20"]);
        $this->assertEquals(124405, $s->value);
        $this->assertDatabaseHas(SettingsConfig::getDatabaseTableName(), [
            'id' => $s->id,
            'value' => 124405
        ]);


        $s->update(['value' => "124407.20"]);
        $this->assertEquals(124407, $s->value);
        $this->assertDatabaseHas(SettingsConfig::getDatabaseTableName(), [
            'id' => $s->id,
            'value' => 124407
        ]);

        $s->update(['value' => true]);
        $this->assertEquals(1, $s->value);
        $this->assertDatabaseHas(SettingsConfig::getDatabaseTableName(), [
            'id' => $s->id,
            'value' => 1
        ]);


        $s->update(['value' => false]);
        $this->assertEquals(0, $s->value);
        $this->assertDatabaseHas(SettingsConfig::getDatabaseTableName(), [
            'id' => $s->id,
            'value' => 0
        ]);

    }

    public function test_cast_value_to_float(): void
    {
        $s = self::createSetting(SettingType::FLOAT);
        $s->update(['value' => "12.4404"]);
        $this->assertEquals(12.4404, $s->value);
        $this->assertDatabaseHas(SettingsConfig::getDatabaseTableName(), [
            'id' => $s->id,
            'value' => 12.4404
        ]);

        $s->update(['value' => "12,12"]);
        $this->assertEquals(12.12, $s->value);
        $this->assertDatabaseHas(SettingsConfig::getDatabaseTableName(), [
            'id' => $s->id,
            'value' => 12.12
        ]);


        $s->update(['value' => "12"]);
        $this->assertEquals(12, $s->value);
        $this->assertDatabaseHas(SettingsConfig::getDatabaseTableName(), [
            'id' => $s->id,
            'value' => 12
        ]);
    }

    public function test_cast_value_to_yes_no(): void
    {
        $s = self::createSetting(SettingType::YES_NO);
        $s->update(['value' => "yes"]);
        $this->assertTrue($s->value);
        $this->assertDatabaseHas(SettingsConfig::getDatabaseTableName(), [
            'id' => $s->id,
            'value' => 1
        ]);

        $s->update(['value' => true]);
        $this->assertTrue($s->value);
        $this->assertDatabaseHas(SettingsConfig::getDatabaseTableName(), [
            'id' => $s->id,
            'value' => 1
        ]);

        $s->update(['value' => false]);
        $this->assertFalse($s->value);
        $this->assertDatabaseHas(SettingsConfig::getDatabaseTableName(), [
            'id' => $s->id,
            'value' => 0
        ]);

        $s->update(['value' => 0]);
        $this->assertFalse($s->value);
        $this->assertDatabaseHas(SettingsConfig::getDatabaseTableName(), [
            'id' => $s->id,
            'value' => 0
        ]);

    }

    public function test_cast_value_to_on_off(): void
    {
        $s = self::createSetting(SettingType::ON_OFF);
        $s->update(['value' => "yes"]);
        $this->assertTrue($s->value);
        $this->assertDatabaseHas(SettingsConfig::getDatabaseTableName(), [
            'id' => $s->id,
            'value' => 1
        ]);

        $s->update(['value' => true]);
        $this->assertTrue($s->value);
        $this->assertDatabaseHas(SettingsConfig::getDatabaseTableName(), [
            'id' => $s->id,
            'value' => 1
        ]);

        $s->update(['value' => false]);
        $this->assertFalse($s->value);
        $this->assertDatabaseHas(SettingsConfig::getDatabaseTableName(), [
            'id' => $s->id,
            'value' => 0
        ]);

        $s->update(['value' => 0]);
        $this->assertFalse($s->value);
        $this->assertDatabaseHas(SettingsConfig::getDatabaseTableName(), [
            'id' => $s->id,
            'value' => 0
        ]);
    }

    public function test_cast_value_to_string(): void
    {
        $s = self::createSetting(SettingType::STRING);
        $s->update(['value' => "test"]);
        $this->assertEquals('test', $s->value);
        $this->assertDatabaseHas(SettingsConfig::getDatabaseTableName(), [
            'id' => $s->id,
            'value' => "test"
        ]);
    }

    public function test_cast_value_to_text(): void
    {
        $s = self::createSetting(SettingType::TEXT);
        $s->update(['value' => "test-long-text"]);
        $this->assertEquals('test-long-text', $s->value);
        $this->assertDatabaseHas(SettingsConfig::getDatabaseTableName(), [
            'id' => $s->id,
            'value' => "test-long-text"
        ]);
    }

    public function test_can_store_null_on_integer()
    {
        $s = self::createSetting(SettingType::INTEGER);
        $s->update(['value' => null]);
        $this->assertEquals(null, $s->value);
        $this->assertDatabaseHas(SettingsConfig::getDatabaseTableName(), [
            'id' => $s->id,
            'value' => null
        ]);
    }

    public function test_can_store_null_on_float()
    {
        $s = self::createSetting(SettingType::FLOAT);
        $s->update(['value' => null]);
        $this->assertEquals(null, $s->value);
        $this->assertDatabaseHas(SettingsConfig::getDatabaseTableName(), [
            'id' => $s->id,
            'value' => null
        ]);
    }

    public function test_can_store_null_on_yes_no()
    {
        $s = self::createSetting(SettingType::YES_NO);
        $s->update(['value' => null]);
        $this->assertEquals(null, $s->value);
        $this->assertDatabaseHas(SettingsConfig::getDatabaseTableName(), [
            'id' => $s->id,
            'value' => null
        ]);
    }

    public function test_can_store_null_on_on_off()
    {
        $s = self::createSetting(SettingType::ON_OFF);
        $s->update(['value' => null]);
        $this->assertEquals(null, $s->value);
        $this->assertDatabaseHas(SettingsConfig::getDatabaseTableName(), [
            'id' => $s->id,
            'value' => null
        ]);
    }

    public function test_can_store_null_on_on_string()
    {
        $s = self::createSetting(SettingType::STRING);
        $s->update(['value' => null]);
        $this->assertEquals(null, $s->value);
        $this->assertDatabaseHas(SettingsConfig::getDatabaseTableName(), [
            'id' => $s->id,
            'value' => null
        ]);

    }

    public function test_can_store_null_on_on_text()
    {
        $s = self::createSetting(SettingType::TEXT);
        $s->update(['value' => null]);
        $this->assertEquals(null, $s->value);
        $this->assertDatabaseHas(SettingsConfig::getDatabaseTableName(), [
            'id' => $s->id,
            'value' => null
        ]);

    }


}
