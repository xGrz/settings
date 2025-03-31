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
            'prefix' => 'test' . rand(1, 200000),
            'suffix' => str($settingType->name)->lower()->toString(),
            'setting_type' => $settingType,
        ])->refresh();
    }

    public function test_cast_value_to_integer(): void
    {
        $s = self::createSetting(SettingType::INTEGER);

        $s->update(['value' => "124404"]);
        $this->assertSame(124404, $s->value);
        $this->assertDatabaseHas(SettingsConfig::getDatabaseTableName(), [
            'id' => $s->id,
            'value' => 124404
        ]);

        $s->update(['value' => "124405.20"]);
        $this->assertSame(124405, $s->value);
        $this->assertDatabaseHas(SettingsConfig::getDatabaseTableName(), [
            'id' => $s->id,
            'value' => 124405
        ]);


        $s->update(['value' => "124407.20"]);
        $this->assertSame(124407, $s->value);
        $this->assertDatabaseHas(SettingsConfig::getDatabaseTableName(), [
            'id' => $s->id,
            'value' => 124407
        ]);

        $s->update(['value' => true]);
        $this->assertSame(1, $s->value);
        $this->assertDatabaseHas(SettingsConfig::getDatabaseTableName(), [
            'id' => $s->id,
            'value' => 1
        ]);


        $s->update(['value' => false]);
        $this->assertSame(0, $s->value);
        $this->assertDatabaseHas(SettingsConfig::getDatabaseTableName(), [
            'id' => $s->id,
            'value' => 0
        ]);

    }

    public function test_cast_value_to_float(): void
    {
        $s = self::createSetting(SettingType::FLOAT);
        $s->update(['value' => "12.4404"]);
        $this->assertSame(12.4404, $s->value);
        $this->assertDatabaseHas(SettingsConfig::getDatabaseTableName(), [
            'id' => $s->id,
            'value' => 12.4404
        ]);

        $s->update(['value' => "12,12"]);
        $this->assertSame(12.12, $s->value);
        $this->assertDatabaseHas(SettingsConfig::getDatabaseTableName(), [
            'id' => $s->id,
            'value' => 12.12
        ]);


        $s->update(['value' => "12"]);
        $this->assertSame(12.0, $s->value);
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
        $this->assertSame('test', $s->value);
        $this->assertDatabaseHas(SettingsConfig::getDatabaseTableName(), [
            'id' => $s->id,
            'value' => "test"
        ]);
    }

    public function test_cast_value_to_text(): void
    {
        $s = self::createSetting(SettingType::TEXT);
        $s->update(['value' => "test-long-text"]);
        $this->assertSame('test-long-text', $s->value);
        $this->assertDatabaseHas(SettingsConfig::getDatabaseTableName(), [
            'id' => $s->id,
            'value' => "test-long-text"
        ]);
    }

    public function test_can_store_null_on_integer()
    {
        $s = self::createSetting(SettingType::INTEGER);
        $s->update(['value' => null]);
        $this->assertNull($s->value);
        $this->assertDatabaseHas(SettingsConfig::getDatabaseTableName(), [
            'id' => $s->id,
            'value' => null
        ]);
    }

    public function test_can_store_null_on_float()
    {
        $s = self::createSetting(SettingType::FLOAT);
        $s->update(['value' => null]);
        $this->assertNull($s->value);
        $this->assertDatabaseHas(SettingsConfig::getDatabaseTableName(), [
            'id' => $s->id,
            'value' => null
        ]);
    }

    public function test_can_store_null_on_yes_no()
    {
        $s = self::createSetting(SettingType::YES_NO);
        $s->update(['value' => null]);
        $this->assertNull($s->value);
        $this->assertDatabaseHas(SettingsConfig::getDatabaseTableName(), [
            'id' => $s->id,
            'value' => null
        ]);
    }

    public function test_can_store_null_on_on_off()
    {
        $s = self::createSetting(SettingType::ON_OFF);
        $s->update(['value' => null]);
        $this->assertNull($s->value);
        $this->assertDatabaseHas(SettingsConfig::getDatabaseTableName(), [
            'id' => $s->id,
            'value' => null
        ]);
    }

    public function test_can_store_null_on_on_string()
    {
        $s = self::createSetting(SettingType::STRING);
        $s->update(['value' => null]);
        $this->assertNull($s->value);
        $this->assertDatabaseHas(SettingsConfig::getDatabaseTableName(), [
            'id' => $s->id,
            'value' => null
        ]);

    }

    public function test_can_store_null_on_on_text()
    {
        $s = self::createSetting(SettingType::TEXT);
        $s->update(['value' => null]);
        $this->assertNull($s->value);
        $this->assertDatabaseHas(SettingsConfig::getDatabaseTableName(), [
            'id' => $s->id,
            'value' => null
        ]);

    }

    public function test_model_return_localized_label_when_on(): void
    {
        $setting = new Setting();
        $setting->setting_type = SettingType::ON_OFF;
        $setting->value = true;

        $this->app->setLocale('en');
        $this->assertEquals('On', $setting->getLabel());

        $this->app->setLocale('pl');
        $this->assertEquals('Włączony', $setting->getLabel());
    }

    public function test_model_return_localized_label_when_off(): void
    {
        $setting = new Setting();
        $setting->setting_type = SettingType::ON_OFF;
        $setting->value = false;

        $this->app->setLocale('en');
        $this->assertEquals('Off', $setting->getLabel());

        $this->app->setLocale('pl');
        $this->assertEquals('Wyłączony', $setting->getLabel());
    }

    public function test_model_return_localized_label_when_yes(): void
    {
        $setting = new Setting();
        $setting->setting_type = SettingType::YES_NO;
        $setting->value = true;

        $this->app->setLocale('en');
        $this->assertEquals('Yes', $setting->getLabel());

        $this->app->setLocale('pl');
        $this->assertEquals('Tak', $setting->getLabel());
    }

    public function test_model_return_localized_label_when_no(): void
    {
        $setting = new Setting();
        $setting->setting_type = SettingType::YES_NO;
        $setting->value = false;

        $this->app->setLocale('en');
        $this->assertEquals('No', $setting->getLabel());

        $this->app->setLocale('pl');
        $this->assertEquals('Nie', $setting->getLabel());
    }

    public function test_model_return_pure_value_label_when_setting_is_not_boolean_type(): void
    {
        $setting = new Setting();
        $setting->setting_type = SettingType::INTEGER;
        $setting->value = 200;

        $this->assertSame(200, $setting->getLabel());

    }

    public function test_model_create_uses_casts()
    {
        Setting::truncate();
        Setting::create([
            'prefix' => 'prefix method - test',
            'suffix' => 'suffix method - test',
            'setting_type' => SettingType::STRING,
        ]);

        $this->assertDatabaseHas(SettingsConfig::getDatabaseTableName(), [
            'prefix' => 'prefixMethodTest',
            'suffix' => 'suffixMethodTest',
            'setting_type' => SettingType::STRING->value,
        ]);
    }
}
