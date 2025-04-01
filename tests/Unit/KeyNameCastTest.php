<?php

namespace XGrz\Settings\Tests\Unit;

use Illuminate\Support\Facades\Config;
use XGrz\Settings\Casts\KeyNameCast;
use XGrz\Settings\Enums\KeyNaming;
use XGrz\Settings\Tests\TestCase;

class KeyNameCastTest extends TestCase
{
    public function test_key_name_set_cast()
    {
        $castedValue = (new KeyNameCast())->set(null, 'prefix', 'some key', []);
        $this->assertSame('someKey', $castedValue);
    }

    public function test_key_name_get_cast()
    {
        // expects return unchanged key
        $castedValue = (new KeyNameCast())->get(null, 'prefix', 'some key', []);
        $this->assertSame('some key', $castedValue);
    }

    public function test_camel_case_key_generator()
    {
        Config::set('app-settings.key_name_generator', KeyNaming::CAMEL_CASE);
        $castedValue = (new KeyNameCast())->set(null, 'prefix', 'app\Filament\Resources\RepairResource+/*!@#$%^&()', []);
        $this->assertSame('appFilamentResourcesRepairResource', $castedValue);
    }

    public function test_snake_case_key_generator()
    {
        Config::set('app-settings.key_name_generator', KeyNaming::SNAKE_CASE);
        $castedValue = (new KeyNameCast())->set(null, 'prefix', 'app\Filament\Resources\RepairResource+/*!@#$%^&()', []);
        $this->assertSame('app_filament_resources_repair_resource', $castedValue);
    }

    public function test_kebab_case_key_generator()
    {
        Config::set('app-settings.key_name_generator', KeyNaming::KEBAB_CASE);
        $castedValue = (new KeyNameCast())->set(null, 'prefix', 'app\Filament\Resources\RepairResource+/*!@#$%^&()', []);
        $this->assertSame('app-filament-resources-repair-resource', $castedValue);
    }

    public function test_default_key_generator()
    {
        Config::set('app-settings.key_name_generator', 'invalid-key-generator');
        $castedValue = (new KeyNameCast())->set(null, 'prefix', 'app\Filament\Resources\RepairResource+/*!@#$%^&()', []);
        $this->assertSame('appFilamentResourcesRepairResource', $castedValue);
    }
}