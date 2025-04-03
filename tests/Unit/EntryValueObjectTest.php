<?php

namespace XGrz\Settings\Tests\Unit;

use XGrz\Settings\Enums\Type;
use XGrz\Settings\Tests\TestCase;
use XGrz\Settings\ValueObjects\Entry;

class EntryValueObjectTest extends TestCase
{
    public function test_can_assign_entry_value()
    {
        $e = Entry::make('test');
        $e2 = Entry::make()->value('test');

        $this->assertArrayHasKey('value', $e->toArray());
        $this->assertSame('test', $e->toArray()['value']);
        $this->assertSame($e->toArray(), $e2->toArray());
    }

    public function test_can_detect_string_value_type()
    {
        $e = Entry::make('test');

        $this->assertEquals(Type::STRING, $e->getType());
    }

    public function test_can_detect_integer_value_type()
    {
        $e = Entry::make(123);

        $this->assertEquals(Type::INTEGER, $e->getType());
    }

    public function test_can_detect_float_value_type()
    {
        $e = Entry::make(123.12);

        $this->assertEquals(Type::FLOAT, $e->getType());
    }

    public function test_can_detect_text_value_type()
    {
        $e = Entry::make(str()->random(201));

        $this->assertEquals(Type::TEXT, $e->getType());
    }

    public function test_can_detect_boolean_value_type()
    {
        $e = Entry::make(true);
        $this->assertTrue($e->getType() === Type::YES_NO);
        $this->assertTrue($e->getValue());
        $this->assertEquals(Type::YES_NO, $e->getType());
    }
}
