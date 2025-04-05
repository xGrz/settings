<?php

namespace XGrz\Settings\Tests\Unit\ValueObjects;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;
use XGrz\Settings\Enums\KeyNaming;
use XGrz\Settings\Enums\Operation;
use XGrz\Settings\Enums\Type;
use XGrz\Settings\Helpers\SettingsConfig;
use XGrz\Settings\Tests\TestCase;
use XGrz\Settings\ValueObjects\SettingItem;

class SettingItemTest extends TestCase
{
    private function createSettingItem(string $keyName = 'test_key', array $definition = [])
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

        return SettingItem::make($settings, $keyName);
    }

    private function randomKeyName(): string
    {
        return Str::random(10) . '.' . Str::random(10);
    }

    public function test_setting_detect_unchanged_item()
    {
        $settingItem = $this->createSettingItem();
        $this->assertSame(Operation::UNCHANGED, $settingItem->getOperationType());
    }

    public function test_setting_item_operation_detects_it_should_be_created()
    {
        $settingItem = $this->createSettingItem(definition: ['storedType' => null, 'storedValue' => null, 'storedDescription' => null]);
        $this->assertEquals('test_key', $settingItem->key);
        $this->assertSame(Operation::CREATE, $settingItem->getOperationType());
    }

    public function test_setting_item_operation_detects_it_should_be_deleted()
    {
        $settingItem = $this->createSettingItem(definition: ['definedType' => null, 'definedValue' => null, 'definedDescription' => null]);
        $this->assertSame(Operation::DELETE, $settingItem->getOperationType());
    }

    public function test_setting_item_operation_detects_it_should_be_updated_when_type_changes_from_string_to_text()
    {
        $settingItem = $this->createSettingItem(definition: ['storedType' => Type::STRING, 'definedType' => Type::TEXT]);
        $this->assertSame(Operation::UPDATE, $settingItem->getOperationType());
    }

    public function test_setting_item_refuses_to_be_updated_when_type_changes_from_text_to_string()
    {
        $settingItem = $this->createSettingItem(definition: ['storedType' => Type::TEXT, 'definedType' => Type::STRING]);
        $this->assertNotEquals(Operation::UPDATE, $settingItem->getOperationType());

        // allow only FORCE-UPDATE
        $this->assertEquals(Operation::FORCE_UPDATE, $settingItem->getOperationType());
    }

    public function test_setting_item_operation_detects_it_should_be_updated_when_type_changes_from_on_off_to_yes_no()
    {
        $settingItem = $this->createSettingItem(definition: ['storedType' => Type::ON_OFF, 'definedType' => Type::YES_NO]);
        $this->assertSame(Operation::UPDATE, $settingItem->getOperationType());
    }

    public function test_setting_item_operation_detects_it_should_be_updated_when_type_changes_from_yes_no_to_on_off()
    {
        $settingItem = $this->createSettingItem(definition: ['storedType' => Type::YES_NO, 'definedType' => Type::ON_OFF]);
        $this->assertSame(Operation::UPDATE, $settingItem->getOperationType());
    }

    public function test_setting_item_operation_detects_it_should_be_updated_when_type_changes_from_integer_to_float()
    {
        $settingItem = $this->createSettingItem(definition: ['storedType' => Type::INTEGER, 'definedType' => Type::FLOAT]);
        $this->assertSame(Operation::UPDATE, $settingItem->getOperationType());
    }

    public function test_setting_item_refuses_to_be_updated_when_type_changes_from_float_to_integer()
    {
        $settingItem = $this->createSettingItem(definition: ['storedType' => Type::FLOAT, 'definedType' => Type::INTEGER]);
        $this->assertSame(Operation::FORCE_UPDATE, $settingItem->getOperationType());
    }

    public function test_setting_created_in_database()
    {
        Config::set('app-settings.key_name_generator', KeyNaming::SNAKE_CASE);
        $key = self::randomKeyName();
        $formattedKey = SettingsConfig::getKeyGeneratorType()->generateKey($key);

        $settingItem = $this->createSettingItem(
            keyName: $key,
            definition: [
                'storedType' => null,
                'storedValue' => null,
                'storedDescription' => null,
            ]);

        $this->assertSame(Operation::CREATE, $settingItem->getOperationType());

        $settingItem->create();
        $this->assertDatabaseHas(SettingsConfig::getDatabaseTableName(), [
            'key' => $formattedKey,
            'type' => $settingItem->definedType,
            'value' => $settingItem->definedValue,
            'description' => $settingItem->definedDescription,
        ]);
    }

    public function test_setting_is_not_created_when_operation_is_not_detected_as_create()
    {

    }

    public function test_setting_is_updated_in_database()
    {

    }

    public function test_setting_is_not_updated_when_operation_is_not_detected_as_update()
    {

    }


    public function test_setting_is_deleted_from_database()
    {

    }

    public function test_setting_is_not_deleted_when_operation_is_not_detected_as_delete()
    {

    }


    public function test_setting_is_forceUpdated_in_database()
    {

    }

    public function test_setting_is_not_forceUpdated_when_operation_is_not_detected_as_force_update()
    {

    }


}
