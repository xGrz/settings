<?php

namespace XGrz\Settings\Tests\Unit;

use Illuminate\Support\Str;
use stdClass;
use XGrz\Settings\Enums\Type;
use XGrz\Settings\Exceptions\UnresolvableValueTypeException;
use XGrz\Settings\Helpers\DetectValueType;
use XGrz\Settings\Tests\TestCase;

class DetectValueTypeTest extends TestCase
{
    public function test_can_detect_integer_values()
    {
        $this->assertSame(Type::INTEGER, DetectValueType::make(123));
        $this->assertNotSame(Type::INTEGER, DetectValueType::make('123'));
    }

    public function test_can_detect_float_values()
    {
        $this->assertSame(Type::FLOAT, DetectValueType::make(1213.12));
        $this->assertNotSame(Type::FLOAT, DetectValueType::make('1213.12'));
    }

    public function test_can_detect_boolean_values()
    {
        $this->assertSame(Type::YES_NO, DetectValueType::make(true));
        $this->assertSame(Type::YES_NO, DetectValueType::make(false));
        $this->assertNotSame(Type::ON_OFF, DetectValueType::make(true));
        $this->assertNotSame(Type::YES_NO, DetectValueType::make(1));
        $this->assertNotSame(Type::YES_NO, DetectValueType::make(0));
    }

    public function test_can_detect_string_values()
    {
        $this->assertSame(Type::STRING, DetectValueType::make(Str::random(200)));
        $this->assertNotSame(Type::STRING, DetectValueType::make(Str::random(201)));
    }

    public function test_can_detect_test_values()
    {
        $this->assertSame(Type::TEXT, DetectValueType::make(Str::random(201)));
        $this->assertNotSame(Type::TEXT, DetectValueType::make(Str::random(200)));
    }

    public function test_throws_exception_when_value_object()
    {
        $this->expectException(UnresolvableValueTypeException::class);
        $this->expectExceptionMessage('Could not detect setting type by its value [object] given.');
        DetectValueType::make(new StdClass());
    }

    public function test_throws_exception_when_value_is_null()
    {
        $this->expectException(UnresolvableValueTypeException::class);
        $this->expectExceptionMessage('Could not detect setting type by its value [NULL] given.');
        DetectValueType::make(null);
    }

    public function test_throws_exception_when_value_is_array()
    {
        $this->expectException(UnresolvableValueTypeException::class);
        $this->expectExceptionMessage('Could not detect setting type by its value [array] given.');
        DetectValueType::make([]);
    }
}