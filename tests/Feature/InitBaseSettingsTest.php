<?php

namespace xGrz\Settings\Tests\Feature;

use Illuminate\Support\Facades\Config;
use xGrz\Settings\Enums\SettingType;
use xGrz\Settings\Helpers\InitBaseSettings;
use xGrz\Settings\Helpers\SettingEntry;
use xGrz\Settings\Helpers\SettingsConfig;
use xGrz\Settings\Models\Setting;
use xGrz\Settings\Tests\TestCase;

class InitBaseSettingsTest extends TestCase
{
    public SettingEntry $entry;

    protected function setUp(): void
    {
        parent::setUp();
        Setting::truncate();
        $defaultConfig = include __DIR__ . '/../../config/definitions.php';
        Config::set('app-settings-definitions', $defaultConfig);
    }

    public function test_init_base_settings()
    {
        InitBaseSettings::make();
        $this->assertDatabaseHas(SettingsConfig::getDatabaseTableName(),
            [
                'prefix' => 'system',
                'suffix' => 'sellerAddressName',
                'key' => 'system.sellerAddressName',
                'description' => 'Seller name',
                'value' => 'Laravel Corporation',
                'context' => null,
                'setting_type' => SettingType::STRING,
            ]
        );

        $this->assertDatabaseHas(SettingsConfig::getDatabaseTableName(),
            [
                'prefix' => 'system',
                'suffix' => 'name',
                'key' => 'system.name',
                'description' => 'Page length2',
                'value' => 12,
                'context' => 'settings',
                'setting_type' => SettingType::INTEGER,
            ]
        );

        $this->assertDatabaseHas(SettingsConfig::getDatabaseTableName(),
            [
                'prefix' => 'system',
                'suffix' => 'yesNo',
                'setting_type' => SettingType::YES_NO,
            ]);
    }


}
