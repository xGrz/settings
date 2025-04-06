<?php

namespace XGrz\Settings\Tests\Feature;

use Illuminate\Support\Collection;
use XGrz\Settings\Actions\GetStoredSettings;
use XGrz\Settings\Enums\Type;
use XGrz\Settings\Models\Setting;
use XGrz\Settings\Tests\TestCase;

class GetStoredSettingsTest extends TestCase
{
    public function test_returns_an_empty_collection_when_no_settings_exist(): void
    {
        Setting::truncate();
        $result = GetStoredSettings::asCollection();

        $this->assertInstanceOf(Collection::class, $result);
        $this->assertTrue($result->isEmpty());
    }

    public function test_returns_a_collection_of_settings_with_keys_as_setting_keys(): void
    {
        Setting::truncate();
        Setting::create(['key' => 'setting.abc', 'value' => 'value1', 'type' => Type::STRING]);
        Setting::create(['key' => 'setting.int', 'value' => 123, 'type' => Type::STRING]);
        $result = GetStoredSettings::asCollection();

        $this->assertInstanceOf(Collection::class, $result);
        $this->assertCount(2, $result);

        $this->assertArrayHasKey('setting.abc', $result->toArray());
        $this->assertArrayHasKey('setting.abc', $result->toArray());

        $this->assertEquals(Setting::where('key', 'setting.abc')->first(), $result->first());
        $this->assertEquals(Setting::where('key', 'setting.int')->first(), $result->last());

    }
}
