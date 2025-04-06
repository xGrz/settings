<?php

namespace XGrz\Settings\Tests\Feature;

use Illuminate\Support\Collection;
use XGrz\Settings\Actions\SettingItemsBuilder;
use XGrz\Settings\Enums\Operation;
use XGrz\Settings\Enums\Type;
use XGrz\Settings\Models\Setting;
use XGrz\Settings\Tests\TestCase;
use XGrz\Settings\ValueObjects\Entry;
use XGrz\Settings\ValueObjects\SettingItem;

class SettingItemBuilderTest extends TestCase
{
    public function test_it_builds_setting_item()
    {
        $builder = SettingItemsBuilder::make(
            collect(['abc' => Entry::make(123, description: 'abc description')]),
            collect(['abc' => (new Setting)->fill(['key' => 'abc', 'value' => 456, 'type' => Type::FLOAT, 'description' => 'stored description'])])
        );

        $this->assertInstanceOf(Collection::class, $builder);
        $this->assertCount(1, $builder);
        $this->assertInstanceOf(SettingItem::class, $builder->first());
        $this->assertSame($builder->first()->key, 'abc');
        $this->assertSame($builder->first()->definedValue, 123);
        $this->assertSame($builder->first()->definedType, Type::INTEGER);
        $this->assertSame($builder->first()->storedValue, 456.0);
        $this->assertSame($builder->first()->storedType, Type::FLOAT);
        $this->assertSame($builder->first()->definedDescription, 'abc description');
        $this->assertSame($builder->first()->storedDescription, 'stored description');
        $this->assertSame(Operation::FORCE_UPDATE, $builder->first()->getOperationType());
    }

    public function test_it_builds_setting_item_with_empty_definitions()
    {
        $builder = SettingItemsBuilder::make(
            collect(),
            collect(['abc' => (new Setting)->fill(['key' => 'abc', 'value' => 456, 'type' => Type::FLOAT, 'description' => 'stored description'])])
        );

        $this->assertInstanceOf(Collection::class, $builder);
        $this->assertInstanceOf(SettingItem::class, $builder->first());
        $this->assertSame($builder->first()->key, 'abc');
        $this->assertNull($builder->first()->definedValue);
        $this->assertNull($builder->first()->definedType);
        $this->assertSame($builder->first()->storedValue, 456.0);
        $this->assertSame($builder->first()->storedType, Type::FLOAT);
        $this->assertNull($builder->first()->definedDescription);
        $this->assertSame($builder->first()->storedDescription, 'stored description');
        $this->assertSame(Operation::DELETE, $builder->first()->getOperationType());
    }

    public function test_it_builds_setting_item_with_empty_stored()
    {
        $builder = SettingItemsBuilder::make(
            collect(['abc' => Entry::make(123, description: 'abc description')]),
            collect()
        );
        $this->assertInstanceOf(Collection::class, $builder);
        $this->assertInstanceOf(SettingItem::class, $builder->first());
        $this->assertSame($builder->first()->key, 'abc');
        $this->assertSame($builder->first()->definedValue, 123);
        $this->assertSame($builder->first()->definedType, Type::INTEGER);
        $this->assertSame($builder->first()->definedDescription, 'abc description');
        $this->assertNull($builder->first()->storedValue);
        $this->assertNull($builder->first()->storedType);
        $this->assertNull($builder->first()->storedDescription);
        $this->assertSame(Operation::CREATE, $builder->first()->getOperationType());
    }
}
