<?php

namespace XGrz\Settings\Tests\Feature\Models;

use Illuminate\Support\Facades\Config;
use XGrz\Settings\Enums\KeyNaming;
use XGrz\Settings\Enums\Type;
use XGrz\Settings\Helpers\Config\SettingsConfig;
use XGrz\Settings\Models\Setting;
use XGrz\Settings\Tests\TestCase;

class SettingModelTest extends TestCase
{
    private function createSetting(Type $settingType): Setting
    {
        return Setting::create([
            'key' => 'test' . rand(1, 200000),
            'value' => 'abc',
            'type' => $settingType,
        ])->refresh();
    }

    public function test_cast_value_to_integer(): void
    {
        $s = self::createSetting(Type::INTEGER);

        $s->update(['value' => '124404']);
        $this->assertSame(124404, $s->value);
        $this->assertDatabaseHas(SettingsConfig::getDatabaseTableName(), [
            'id' => $s->id,
            'value' => 124404,
        ]);

        $s->update(['value' => '124405.20']);
        $this->assertSame(124405, $s->value);
        $this->assertDatabaseHas(SettingsConfig::getDatabaseTableName(), [
            'id' => $s->id,
            'value' => 124405,
        ]);

        $s->update(['value' => '124407.20']);
        $this->assertSame(124407, $s->value);
        $this->assertDatabaseHas(SettingsConfig::getDatabaseTableName(), [
            'id' => $s->id,
            'value' => 124407,
        ]);

        $s->update(['value' => true]);
        $this->assertSame(1, $s->value);
        $this->assertDatabaseHas(SettingsConfig::getDatabaseTableName(), [
            'id' => $s->id,
            'value' => 1,
        ]);

        $s->update(['value' => false]);
        $this->assertSame(0, $s->value);
        $this->assertDatabaseHas(SettingsConfig::getDatabaseTableName(), [
            'id' => $s->id,
            'value' => 0,
        ]);
    }

    public function test_cast_value_to_float(): void
    {
        $s = self::createSetting(Type::FLOAT);
        $s->update(['value' => '12.4404']);
        $this->assertSame(12.4404, $s->value);
        $this->assertDatabaseHas(SettingsConfig::getDatabaseTableName(), [
            'id' => $s->id,
            'value' => 12.4404,
        ]);

        $s->update(['value' => '12,12']);
        $this->assertSame(12.12, $s->value);
        $this->assertDatabaseHas(SettingsConfig::getDatabaseTableName(), [
            'id' => $s->id,
            'value' => 12.12,
        ]);

        $s->update(['value' => '12']);
        $this->assertSame(12.0, $s->value);
        $this->assertDatabaseHas(SettingsConfig::getDatabaseTableName(), [
            'id' => $s->id,
            'value' => 12,
        ]);
    }

    public function test_cast_value_to_yes_no(): void
    {
        $s = self::createSetting(Type::YES_NO);
        $s->update(['value' => 'yes']);
        $this->assertTrue($s->value);
        $this->assertDatabaseHas(SettingsConfig::getDatabaseTableName(), [
            'id' => $s->id,
            'value' => 1,
        ]);

        $s->update(['value' => true]);
        $this->assertTrue($s->value);
        $this->assertDatabaseHas(SettingsConfig::getDatabaseTableName(), [
            'id' => $s->id,
            'value' => 1,
        ]);

        $s->update(['value' => false]);
        $this->assertFalse($s->value);
        $this->assertDatabaseHas(SettingsConfig::getDatabaseTableName(), [
            'id' => $s->id,
            'value' => 0,
        ]);

        $s->update(['value' => 0]);
        $this->assertFalse($s->value);
        $this->assertDatabaseHas(SettingsConfig::getDatabaseTableName(), [
            'id' => $s->id,
            'value' => 0,
        ]);
    }

    public function test_cast_value_to_on_off(): void
    {
        $s = self::createSetting(Type::ON_OFF);
        $s->update(['value' => 'yes']);
        $this->assertTrue($s->value);
        $this->assertDatabaseHas(SettingsConfig::getDatabaseTableName(), [
            'id' => $s->id,
            'value' => 1,
        ]);

        $s->update(['value' => true]);
        $this->assertTrue($s->value);
        $this->assertDatabaseHas(SettingsConfig::getDatabaseTableName(), [
            'id' => $s->id,
            'value' => 1,
        ]);

        $s->update(['value' => false]);
        $this->assertFalse($s->value);
        $this->assertDatabaseHas(SettingsConfig::getDatabaseTableName(), [
            'id' => $s->id,
            'value' => 0,
        ]);

        $s->update(['value' => 0]);
        $this->assertFalse($s->value);
        $this->assertDatabaseHas(SettingsConfig::getDatabaseTableName(), [
            'id' => $s->id,
            'value' => 0,
        ]);
    }

    public function test_cast_value_to_string(): void
    {
        $s = self::createSetting(Type::STRING);
        $s->update(['value' => 'test']);
        $this->assertSame('test', $s->value);
        $this->assertDatabaseHas(SettingsConfig::getDatabaseTableName(), [
            'id' => $s->id,
            'value' => 'test',
        ]);
    }

    public function test_cast_value_to_text(): void
    {
        $s = self::createSetting(Type::TEXT);
        $s->update(['value' => 'test-long-text']);
        $this->assertSame('test-long-text', $s->value);
        $this->assertDatabaseHas(SettingsConfig::getDatabaseTableName(), [
            'id' => $s->id,
            'value' => 'test-long-text',
        ]);
    }

    public function test_can_store_null_on_integer()
    {
        $s = self::createSetting(Type::INTEGER);
        $s->update(['value' => NULL]);
        $this->assertNull($s->value);
        $this->assertDatabaseHas(SettingsConfig::getDatabaseTableName(), [
            'id' => $s->id,
            'value' => NULL,
        ]);
    }

    public function test_can_store_null_on_float()
    {
        $s = self::createSetting(Type::FLOAT);
        $s->update(['value' => NULL]);
        $this->assertNull($s->value);
        $this->assertDatabaseHas(SettingsConfig::getDatabaseTableName(), [
            'id' => $s->id,
            'value' => NULL,
        ]);
    }

    public function test_can_store_null_on_yes_no()
    {
        $s = self::createSetting(Type::YES_NO);
        $s->update(['value' => NULL]);
        $this->assertNull($s->value);
        $this->assertDatabaseHas(SettingsConfig::getDatabaseTableName(), [
            'id' => $s->id,
            'value' => NULL,
        ]);
    }

    public function test_can_store_null_on_on_off()
    {
        $s = self::createSetting(Type::ON_OFF);
        $s->update(['value' => NULL]);
        $this->assertNull($s->value);
        $this->assertDatabaseHas(SettingsConfig::getDatabaseTableName(), [
            'id' => $s->id,
            'value' => NULL,
        ]);
    }

    public function test_can_store_null_on_on_string()
    {
        $s = self::createSetting(Type::STRING);
        $s->update(['value' => NULL]);
        $this->assertNull($s->value);
        $this->assertDatabaseHas(SettingsConfig::getDatabaseTableName(), [
            'id' => $s->id,
            'value' => NULL,
        ]);
    }

    public function test_can_store_null_on_on_text()
    {
        $s = self::createSetting(Type::TEXT);
        $s->update(['value' => NULL]);
        $this->assertNull($s->value);
        $this->assertDatabaseHas(SettingsConfig::getDatabaseTableName(), [
            'id' => $s->id,
            'value' => NULL,
        ]);
    }

    public function test_model_return_localized_label_when_on(): void
    {
        $setting = new Setting;
        $setting->type = Type::ON_OFF;
        $setting->value = true;

        $this->app->setLocale('en');
        $this->assertEquals('On', $setting->getLabel());

        $this->app->setLocale('pl');
        $this->assertEquals('Włączony', $setting->getLabel());
    }

    public function test_model_return_localized_label_when_off(): void
    {
        $setting = new Setting;
        $setting->type = Type::ON_OFF;
        $setting->value = false;

        $this->app->setLocale('en');
        $this->assertEquals('Off', $setting->getLabel());

        $this->app->setLocale('pl');
        $this->assertEquals('Wyłączony', $setting->getLabel());
    }

    public function test_model_return_localized_label_when_yes(): void
    {
        $setting = new Setting;
        $setting->type = Type::YES_NO;
        $setting->value = true;

        $this->app->setLocale('en');
        $this->assertEquals('Yes', $setting->getLabel());

        $this->app->setLocale('pl');
        $this->assertEquals('Tak', $setting->getLabel());
    }

    public function test_model_return_localized_label_when_no(): void
    {
        $setting = new Setting;
        $setting->type = Type::YES_NO;
        $setting->value = false;

        $this->app->setLocale('en');
        $this->assertEquals('No', $setting->getLabel());

        $this->app->setLocale('pl');
        $this->assertEquals('Nie', $setting->getLabel());
    }

    public function test_model_returns_false_flag_label_when_is_boolean_type()
    {
        $setting = (new Setting)->fill(['type' => Type::STRING, 'value' => 'abc']);
        $this->assertFalse($setting->getLabel());

        $setting = (new Setting)->fill(['type' => Type::INTEGER, 'value' => 123]);
        $this->assertFalse($setting->getLabel());
    }

    public function test_model_refresh_key()
    {
        Config::set('app-settings.key_name_generator', KeyNaming::SNAKE_CASE);
        $setting = Setting::create(['key' => 'test my key . abc !@# Test', 'value' => 'abc', 'type' => Type::STRING]);

        $this->assertDatabaseHas(SettingsConfig::getDatabaseTableName(), [
            'key' => 'test_my_key.abc_test',
        ]);

        Config::set('app-settings.key_name_generator', KeyNaming::KEBAB_CASE);
        $setting->refreshKey();
        $this->assertDatabaseHas(SettingsConfig::getDatabaseTableName(), [
            'key' => 'test-my-key.abc-test',
        ]);

        Config::set('app-settings.key_name_generator', KeyNaming::CAMEL_CASE);
        $setting->refreshKey();
        $this->assertDatabaseHas(SettingsConfig::getDatabaseTableName(), [
            'key' => 'testMyKey.abcTest',
        ]);

        Config::set('app-settings.key_name_generator', KeyNaming::SNAKE_CASE);
        $setting->refreshKey();
        $this->assertDatabaseHas(SettingsConfig::getDatabaseTableName(), [
            'key' => 'test_my_key.abc_test',
        ]);
    }

    public function test_model_has_boolean_value()
    {
        $setting = (new Setting)->fill(['type' => Type::ON_OFF]);
        $this->assertTrue($setting->isBoolean());

        $setting = (new Setting)->fill(['type' => Type::YES_NO]);
        $this->assertTrue($setting->isBoolean());

        $setting = (new Setting)->fill(['type' => Type::INTEGER]);
        $this->assertFalse($setting->isBoolean());

        $setting = (new Setting)->fill(['type' => Type::FLOAT]);
        $this->assertFalse($setting->isBoolean());

        $setting = (new Setting)->fill(['type' => Type::STRING]);
        $this->assertFalse($setting->isBoolean());

        $setting = (new Setting)->fill(['type' => Type::TEXT]);
        $this->assertFalse($setting->isBoolean());
    }
}