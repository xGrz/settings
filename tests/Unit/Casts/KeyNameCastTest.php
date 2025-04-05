<?php

namespace XGrz\Settings\Tests\Unit\Casts;

use Illuminate\Support\Facades\Config;
use XGrz\Settings\Casts\KeyNameCast;
use XGrz\Settings\Enums\KeyNaming;
use XGrz\Settings\Tests\TestCase;

class KeyNameCastTest extends TestCase
{
    public function test_get_casted_key_name_should_trim_value()
    {
        $cast = (new KeyNameCast)->get(null, 'key', '  some value  ', []);
        $this->assertSame('some value', $cast);
    }

    public function test_set_cast_key_name_with_camel_case()
    {
        Config::set('app-settings.key_name_generator', KeyNaming::CAMEL_CASE);
        $cast = (new KeyNameCast)->set(null, 'key', '  some value  . test !@ ', []);
        $this->assertSame('someValue.test', $cast);
    }

    public function test_set_cast_key_name_with_kebab_case()
    {
        Config::set('app-settings.key_name_generator', KeyNaming::KEBAB_CASE);
        $cast = (new KeyNameCast)->set(null, 'key', '  some value  . test !@ ', []);
        $this->assertSame('some-value.test', $cast);
    }

    public function test_set_cast_key_name_with_snake_case()
    {
        Config::set('app-settings.key_name_generator', KeyNaming::SNAKE_CASE);
        $cast = (new KeyNameCast)->set(null, 'key', '  some value  . test !@ ', []);
        $this->assertSame('some_value.test', $cast);
    }
}