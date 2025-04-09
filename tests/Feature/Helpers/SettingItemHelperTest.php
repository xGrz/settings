<?php

namespace XGrz\Settings\Tests\Feature\Helpers;

use XGrz\Settings\Enums\Operation;
use XGrz\Settings\Enums\Type;
use XGrz\Settings\Exceptions\SettingItemDefinitionException;
use XGrz\Settings\Tests\TestCase;
use XGrz\Settings\ValueObjects\SettingItem;

class SettingItemHelperTest extends TestCase
{

    public function test_should_throw_exception_when_types_are_not_defined()
    {
        $this->expectException(SettingItemDefinitionException::class);
        $item = SettingItem::make([
            'definedValue' => NULL,
            'definedDescription' => 'Some description',
            'storedValue' => NULL,
            'storedDescription' => 'Some description',
        ], 'test-key');
    }

    public function test_should_recognize_setting_unchanged_type()
    {
        $item = SettingItem::make([
            'definedType' => Type::YES_NO,
            'definedValue' => true,
            'definedDescription' => 'Some description',
            'storedType' => Type::YES_NO,
            'storedValue' => true,
            'storedDescription' => 'Some description',
        ], 'test-key');

        $this->assertSame(Operation::UNCHANGED, $item->getOperationType());
    }

    public function test_should_recognize_setting_create_operation_type()
    {
        $item = SettingItem::make([
            'definedType' => Type::YES_NO,
            'definedValue' => true,
            'definedDescription' => 'Some description',
        ], 'test-key');

        $this->assertSame(Operation::CREATE, $item->getOperationType());
    }

    public function test_should_recognize_setting_delete_operation_type()
    {
        $item = SettingItem::make([
            'storedType' => Type::YES_NO,
            'storedValue' => true,
            'storedDescription' => 'Some description',
        ], 'test-key');

        $this->assertSame(Operation::DELETE, $item->getOperationType());
    }

    public function test_should_recognize_setting_update_operation_type_on_description_changed()
    {
        $item = SettingItem::make([
            'definedType' => Type::YES_NO,
            'definedValue' => true,
            'definedDescription' => 'Some description updated',
            'storedType' => Type::YES_NO,
            'storedValue' => true,
            'storedDescription' => 'Some description',
        ], 'test-key');

        $this->assertSame(Operation::UPDATE, $item->getOperationType());
    }

    public function test_should_recognize_setting_update_operation_type_on_key_changed_without_value_data_loss()
    {
        $item = SettingItem::make([
            'definedType' => Type::YES_NO,
            'definedValue' => true,
            'definedDescription' => 'Some description updated',
            'storedType' => Type::ON_OFF,
            'storedValue' => true,
            'storedDescription' => 'Some description',
        ], 'test-key');

        $this->assertSame(Operation::UPDATE, $item->getOperationType());
    }

    public function test_should_recognize_setting_forceUpdate_operation_type_on_key_changed_with_value_data_loss()
    {
        $item = SettingItem::make([
            'definedType' => Type::STRING,
            'definedValue' => 'Some value',
            'definedDescription' => 'Some description',
            'storedType' => Type::ON_OFF,
            'storedValue' => true,
            'storedDescription' => 'Some description',
        ], 'test-key');

        $this->assertSame(Operation::FORCE_UPDATE, $item->getOperationType());
    }

    public function test_should_recognize_setting_forceUpdate_operation_type_on_key_changed_with_value_data_loss_and_description_changed()
    {
        $item = SettingItem::make([
            'definedType' => Type::STRING,
            'definedValue' => 'Some value',
            'definedDescription' => 'Some description updated',
            'storedType' => Type::ON_OFF,
            'storedValue' => true,
            'storedDescription' => 'Some description',
        ], 'test-key');

        $this->assertSame(Operation::FORCE_UPDATE, $item->getOperationType());
    }

}