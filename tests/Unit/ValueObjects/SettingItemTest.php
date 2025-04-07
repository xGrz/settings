<?php

namespace XGrz\Settings\Tests\Unit\ValueObjects;

use Illuminate\Support\Facades\Config;
use XGrz\Settings\Enums\KeyNaming;
use XGrz\Settings\Enums\Operation;
use XGrz\Settings\Enums\Type;
use XGrz\Settings\Helpers\SettingsConfig;
use XGrz\Settings\Models\Setting;
use XGrz\Settings\Tests\TestCase;
use XGrz\Settings\ValueObjects\SettingItem;

class SettingItemTest extends TestCase
{
    private function createSettingItem(string $keyName = 'test_key', array $definition = []): SettingItem
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

    private function prepareCreatableSettingItem(string $keyName = 'test_key'): SettingItem
    {
        Setting::truncate();
        Config::set('app-settings.key_name_generator', KeyNaming::SNAKE_CASE);

        return $this->createSettingItem(
            keyName: $keyName,
            definition: [
                'storedType' => NULL,
                'storedValue' => NULL,
                'storedDescription' => NULL,
            ]);
    }

    private function prepareUpdatableSettingItem(string $keyName = 'test_key'): SettingItem
    {
        Setting::truncate();
        $this->createSettingItem($keyName, [
            'definedType' => Type::STRING,
            'definedValue' => 'example',
            'definedDescription' => 'A test description',
            'storedType' => NULL,
            'storedValue' => NULL,
            'storedDescription' => NULL,
        ])->store();

        return $this->createSettingItem($keyName, [
            'definedType' => Type::TEXT,
            'definedValue' => 'example',
            'definedDescription' => 'A test description',
            'storedType' => Type::STRING,
            'storedValue' => 'example',
            'storedDescription' => 'A test description',
        ]);
    }

    private function prepareDeletableSettingItem(string $keyName = 'test_key'): SettingItem
    {
        Setting::truncate();
        Config::set('app-settings.key_name_generator', KeyNaming::SNAKE_CASE);
        Setting::create(['key' => $keyName, 'type' => Type::STRING, 'value' => 'example', 'description' => 'A test description']);

        return $this->createSettingItem($keyName, [
            'definedType' => NULL,
            'definedValue' => NULL,
            'definedDescription' => NULL,
            'storedType' => Type::STRING,
            'storedValue' => 'example',
            'storedDescription' => 'A test description',
        ]);
    }

    private function prepareForceUpdatableSettingItem(string $keyName = 'test_key'): SettingItem
    {
        Setting::truncate();
        Config::set('app-settings.key_name_generator', KeyNaming::SNAKE_CASE);
        $this->createSettingItem($keyName, [
            'definedType' => Type::STRING,
            'definedValue' => 'example',
            'definedDescription' => 'A test description',
            'storedType' => NULL,
            'storedValue' => NULL,
            'storedDescription' => NULL,
        ])->store();

        return $this->createSettingItem($keyName, [
            'definedType' => Type::ON_OFF,
            'definedValue' => '1',
            'definedDescription' => 'A test description',
            'storedType' => Type::STRING,
            'storedValue' => 'example',
            'storedDescription' => 'A test description',
        ]);
    }

    public function test_setting_detect_unchanged_item()
    {
        $settingItem = $this->createSettingItem();
        $this->assertSame(Operation::UNCHANGED, $settingItem->getOperationType());
    }

    public function test_setting_item_operation_detects_it_should_be_created()
    {
        Config::set('app-settings.key_name_generator', KeyNaming::SNAKE_CASE);
        $settingItem = $this->createSettingItem(definition: ['storedType' => NULL, 'storedValue' => NULL, 'storedDescription' => NULL]);
        $this->assertEquals('test_key', $settingItem->key);
        $this->assertSame(Operation::CREATE, $settingItem->getOperationType());
    }

    public function test_setting_item_operation_detects_it_should_be_deleted()
    {
        $settingItem = $this->createSettingItem(definition: ['definedType' => NULL, 'definedValue' => NULL, 'definedDescription' => NULL]);
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
        $settingItem = self::prepareCreatableSettingItem();

        $this->assertSame(Operation::CREATE, $settingItem->getOperationType());
        $this->assertDatabaseMissing(SettingsConfig::getDatabaseTableName(), [
            'key' => $settingItem->key,
        ]);
        $this->assertTrue($settingItem->store());
        $this->assertDatabaseHas(SettingsConfig::getDatabaseTableName(), [
            'key' => $settingItem->key,
            'type' => $settingItem->definedType,
            'value' => $settingItem->definedValue,
            'description' => $settingItem->definedDescription,
        ]);
    }

    public function test_setting_is_not_created_when_operation_is_not_detected_as_create()
    {
        $settingItem = self::createSettingItem();
        $this->assertNotSame(Operation::CREATE, $settingItem->getOperationType());
        $this->expectsDatabaseQueryCount(0);
        $this->assertFalse($settingItem->store());
    }

    public function test_setting_is_updated_in_database()
    {
        $settingItem = self::prepareUpdatableSettingItem();
        $this->assertSame(Operation::UPDATE, $settingItem->getOperationType());

        $this->expectsDatabaseQueryCount(4); // select, update, refresh cache, database check
        $this->assertTrue($settingItem->store());
        $this->assertDatabaseHas(SettingsConfig::getDatabaseTableName(), [
            'key' => $settingItem->key,
            'type' => Type::TEXT,
        ]);
    }

    public function test_setting_is_not_updated_when_operation_is_not_detected_as_update()
    {
        $settingItem = self::createSettingItem();
        $this->assertNotSame(Operation::UPDATE, $settingItem->getOperationType());
        $this->expectsDatabaseQueryCount(0);
        $this->assertFalse($settingItem->store());
    }

    public function test_setting_is_deleted_from_database()
    {
        $settingItem = self::prepareDeletableSettingItem();
        $this->assertSame(Operation::DELETE, $settingItem->getOperationType());
        $this->expectsDatabaseQueryCount(3);  // select and delete item as separate queries. Last query is database row check
        $this->assertTrue($settingItem->store());
        $this->assertDatabaseMissing(SettingsConfig::getDatabaseTableName(), [
            'key' => $settingItem->key,
        ]);
    }

    public function test_setting_is_not_deleted_when_operation_is_not_detected_as_delete()
    {
        $settingItem = self::createSettingItem();
        $this->assertNotSame(Operation::DELETE, $settingItem->getOperationType());
        $this->expectsDatabaseQueryCount(0);
        $this->assertFalse($settingItem->store());
    }

    public function test_setting_is_force_updated_in_database()
    {
        $settingItem = self::prepareForceUpdatableSettingItem();
        $this->assertSame(Operation::FORCE_UPDATE, $settingItem->getOperationType());
        $this->expectsDatabaseQueryCount(4); // select, update, refresh cache, database check
        $this->assertTrue($settingItem->store());
        $this->assertDatabaseHas(SettingsConfig::getDatabaseTableName(), [
            'key' => $settingItem->key,
            'type' => Type::ON_OFF,
            'value' => true,
        ]);
    }

    public function test_setting_is_not_force_updated_when_operation_is_not_detected_as_force_update()
    {
        $settingItem = self::createSettingItem();
        $this->assertNotSame(Operation::FORCE_UPDATE, $settingItem->getOperationType());
        $this->expectsDatabaseQueryCount(0);
        $this->assertFalse($settingItem->store());
    }
}
