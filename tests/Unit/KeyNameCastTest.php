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
        // expects do not change fetched key
        $castedValue = (new KeyNameCast())->get(null, 'prefix', 'some key', []);
        $this->assertSame('some key', $castedValue);
    }
}