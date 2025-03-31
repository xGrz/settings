<?php

namespace XGrz\Settings\Tests\Unit;

use XGrz\Settings\Casts\KeyNameCast;
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

    public function test_key_name_with_slashes_set_cast()
    {
        $castedValue = (new KeyNameCast())->set(null, 'prefix', 'app\Filament\Resources\RepairResource+/*!@#$%^&()', []);
        $this->assertSame('appFilamentResourcesRepairResource', $castedValue);
    }
}