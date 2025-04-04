<?php

namespace XGrz\Settings\Tests\Unit;

use PHPUnit\Framework\TestCase;
use XGrz\Settings\Enums\Operation;
use XGrz\Settings\Enums\Type;
use XGrz\Settings\ValueObjects\SettingItem;

class SettingItemTest extends TestCase
{
    private function createSettingItem(?string $keyName = null, array $definition = [])
    {
        $settings = [
            'definedType' => Type::STRING,
            'storedType' => Type::STRING,
            'definedValue' => 'example',
            'storedValue' => 'example',
            'definedDescription' => 'A test description',
            'storedDescription' => 'A stored description',
        ];

        foreach ($definition as $key => $value) {
            $settings[$key] = $value;
        }

        return SettingItem::make($settings, $keyName ?? 'test_key');
    }

    public function test_setting_item_detects_it_should_be_created()
    {
        $settingItem = $this->createSettingItem(definition: ['storedType' => null, 'storedValue' => null, 'storedDescription' => null]);
        $this->assertEquals('test_key', $settingItem->key);
        $this->assertSame(Operation::CREATE, $settingItem->getOperationType());
    }

    public function test_setting_item_detects_it_should_be_deleted()
    {
        $settingItem = $this->createSettingItem(definition: ['definedType' => null, 'definedValue' => null, 'definedDescription' => null]);
        $this->assertSame(Operation::DELETE, $settingItem->getOperationType());
    }

    public function test_setting_item_detects_it_should_be_updated_when_type_changes_from_string_to_text()
    {
        $settingItem = $this->createSettingItem(definition: ['storedType' => Type::STRING, 'definedType' => Type::TEXT]);
        $this->assertSame(Operation::UPDATE, $settingItem->getOperationType());
    }

    public function test_setting_item_detects_it_cannot_be_updated_when_type_changes_from_text_to_string()
    {
        $settingItem = $this->createSettingItem(definition: ['storedType' => Type::TEXT, 'definedType' => Type::STRING]);
        $this->assertNotEquals(Operation::UPDATE, $settingItem->getOperationType());
    }

    public function test_setting_item_detects_it_should_be_updated_when_type_changes_from_on_off_to_yes_no()
    {
        $settingItem = $this->createSettingItem(definition: ['storedType' => Type::ON_OFF, 'definedType' => Type::YES_NO]);
        $this->assertSame(Operation::UPDATE, $settingItem->getOperationType());
    }

    public function test_setting_item_detects_it_should_be_updated_when_type_changes_from_yes_no_to_on_off()
    {
        $settingItem = $this->createSettingItem(definition: ['storedType' => Type::YES_NO, 'definedType' => Type::ON_OFF]);
        $this->assertSame(Operation::UPDATE, $settingItem->getOperationType());
    }

    public function test_setting_item_detects_it_should_be_updated_when_type_changes_from_integer_to_float()
    {
        $settingItem = $this->createSettingItem(definition: ['storedType' => Type::INTEGER, 'definedType' => Type::FLOAT]);
        $this->assertSame(Operation::UPDATE, $settingItem->getOperationType());
    }

    public function test_setting_item_detects_it_cannot_be_updated_when_type_changes_from_float_to_integer()
    {
        $settingItem = $this->createSettingItem(definition: ['storedType' => Type::FLOAT, 'definedType' => Type::INTEGER]);
        $this->assertNotSame(Operation::UPDATE, $settingItem->getOperationType());
    }
}
