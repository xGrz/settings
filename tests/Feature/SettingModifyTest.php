<?php

namespace XGrz\Settings\Tests\Feature;

use Illuminate\Support\Facades\Config;
use XGrz\Settings\Enums\KeyNaming;
use XGrz\Settings\Enums\SettingType;
use XGrz\Settings\Helpers\InitBaseSettings;
use XGrz\Settings\Helpers\SettingEntry;
use XGrz\Settings\Helpers\SettingsConfig;
use XGrz\Settings\Models\Setting;
use XGrz\Settings\Tests\TestCase;

class SettingModifyTest extends TestCase
{
    public SettingEntry $entry;

    protected function setUp(): void
    {
        parent::setUp();
        Setting::truncate();
        InitBaseSettings::make();
    }


    public function test_can_modify_prefix()
    {
        $setting = Setting::firstOrFail();
        $setting->fill(['prefix' => 'global']);
        $setting->save();

        $this->assertDatabaseHas(SettingsConfig::getDatabaseTableName(), [
            'id' => $setting->id,
            'prefix' => 'global'
        ]);
    }

    public function test_modified_prefix_is_camel_case_formatted()
    {
        $setting = Setting::firstOrFail();
        $setting->update(['prefix' => 'global system_settings']);

        $this->assertDatabaseHas(SettingsConfig::getDatabaseTableName(), [
            'id' => $setting->id,
            'prefix' => 'globalSystemSettings'
        ]);
    }

    public function test_modified_suffix_is_camel_case_formatted()
    {
        $setting = Setting::firstOrFail();
        $setting->update(['suffix' => 'global system_settings']);

        $this->assertDatabaseHas(SettingsConfig::getDatabaseTableName(), [
            'id' => $setting->id,
            'suffix' => 'globalSystemSettings'
        ]);
    }

    private function createSettingWithType(SettingType $type): Setting
    {
        Setting::factory()->create(['setting_type' => $type])->save();
        $setting = Setting::orderBy('id', 'desc')->first();
        $this->assertDatabaseHas(SettingsConfig::getDatabaseTableName(), [
            'id' => $setting->id,
            'setting_type' => $type
        ]);
        return $setting;
    }

    public function test_change_setting_type_from_on_off_to_yes_no_allowed()
    {
        $setting = $this->createSettingWithType(SettingType::ON_OFF);
        $setting->update(['setting_type' => SettingType::YES_NO]);

        $this->assertDatabaseHas(SettingsConfig::getDatabaseTableName(), [
            'id' => $setting->id,
            'setting_type' => SettingType::YES_NO
        ]);
    }

    public function test_change_setting_type_from_yes_no_to_on_off_allowed()
    {
        $setting = $this->createSettingWithType(SettingType::YES_NO);
        $setting->update(['setting_type' => SettingType::ON_OFF]);

        $this->assertDatabaseHas(SettingsConfig::getDatabaseTableName(), [
            'id' => $setting->id,
            'setting_type' => SettingType::ON_OFF
        ]);
    }

    public function test_change_setting_type_from_on_off_to_other_disallowed()
    {
        $setting = $this->createSettingWithType(SettingType::YES_NO);

        $setting->update(['setting_type' => SettingType::INTEGER]);
        $this->assertDatabaseHas(SettingsConfig::getDatabaseTableName(), [
            'id' => $setting->id,
            'setting_type' => SettingType::YES_NO
        ]);

        $setting->update(['setting_type' => SettingType::FLOAT]);
        $this->assertDatabaseHas(SettingsConfig::getDatabaseTableName(), [
            'id' => $setting->id,
            'setting_type' => SettingType::YES_NO
        ]);

        $setting->update(['setting_type' => SettingType::TEXT]);
        $this->assertDatabaseHas(SettingsConfig::getDatabaseTableName(), [
            'id' => $setting->id,
            'setting_type' => SettingType::YES_NO
        ]);

        $setting->update(['setting_type' => SettingType::STRING]);
        $this->assertDatabaseHas(SettingsConfig::getDatabaseTableName(), [
            'id' => $setting->id,
            'setting_type' => SettingType::YES_NO
        ]);

    }

    public function test_change_setting_type_from_string_to_text_allowed()
    {
        $setting = $this->createSettingWithType(SettingType::STRING);
        $setting->update(['setting_type' => SettingType::TEXT]);

        $this->assertDatabaseHas(SettingsConfig::getDatabaseTableName(), [
            'id' => $setting->id,
            'setting_type' => SettingType::TEXT
        ]);
    }

    public function test_change_setting_type_form_text_to_string_disallowed()
    {
        $setting = $this->createSettingWithType(SettingType::TEXT);
        $setting->update(['setting_type' => SettingType::STRING]);

        $this->assertDatabaseHas(SettingsConfig::getDatabaseTableName(), [
            'id' => $setting->id,
            'setting_type' => SettingType::TEXT
        ]);
    }

    public function test_change_setting_type_from_int_to_float_allowed()
    {
        $setting = $this->createSettingWithType(SettingType::INTEGER);
        $setting->update(['setting_type' => SettingType::FLOAT]);

        $this->assertDatabaseHas(SettingsConfig::getDatabaseTableName(), [
            'id' => $setting->id,
            'setting_type' => SettingType::FLOAT
        ]);
    }

    public function test_change_setting_type_form_float_to_int_disallowed()
    {
        $setting = $this->createSettingWithType(SettingType::FLOAT);
        $setting->update(['setting_type' => SettingType::INTEGER]);

        $this->assertDatabaseHas(SettingsConfig::getDatabaseTableName(), [
            'id' => $setting->id,
            'setting_type' => SettingType::FLOAT
        ]);
    }

    public function test_key_unchanged_after_key_generator_naming_change_in_config()
    {
        Setting::truncate();
        Config::set('app-settings.key_name_generator', KeyNaming::CAMEL_CASE);
        Setting::create(['prefix' => 'system ABC', 'suffix' => 'name ABC', 'setting_type' => SettingType::STRING]);
        $this->assertDatabaseHas(SettingsConfig::getDatabaseTableName(), [
            'key' => 'systemABC.nameABC',
            'value' => null,
        ]);

        Config::set('app-settings.key_name_generator', KeyNaming::SNAKE_CASE);
        $setting = Setting::where('key', 'systemABC.nameABC')->first();
        $setting->update(['value' => 'new value']);
        $this->assertDatabaseHas(SettingsConfig::getDatabaseTableName(), [
            'key' => 'systemABC.nameABC',
            'value' => 'new value',
        ]);
    }


}
