<?php

namespace XGrz\Settings\Tests\Unit;

use Illuminate\Support\Facades\Config;
use XGrz\Settings\Enums\Type;
use XGrz\Settings\Tests\TestCase;
use XGrz\Settings\ValueObjects\Entry;

class EntryHelperTest extends TestCase
{
    public function test_it_creates_an_entry_with_null_values(): void
    {
        $entry = Entry::make();

        $this->assertInstanceOf(Entry::class, $entry);
    }

    public function test_it_creates_setting_entry_with_nested_key()
    {
        $entry = Entry::make('testValue', Type::INTEGER, 'Some description')
            ->appendKey('system')
            ->appendKey('android')
            ->appendKey('app');

        $this->assertInstanceOf(Entry::class, $entry);
        $this->assertArrayHasKey('key', $entry->toArray());
        $this->assertArrayHasKey('value', $entry->toArray());
        $this->assertArrayHasKey('description', $entry->toArray());
        $this->assertArrayHasKey('type', $entry->toArray());
        $this->assertCount(4, $entry->toArray());

        $this->assertSame('system.android.app', $entry->toArray()['key']);
    }

    private function generateFakeEntry(): Entry
    {
        return Entry::make('testValue', Type::INTEGER, 'Some description')
            ->appendKey('system name')
            ->appendKey('android v10')
            ->appendKey('multi words suffix with special chars !@#$%^&*()_+=-[]\\|;:\\,/"<>? and space ');
    }

    public function test_it_generates_keys_with_camel_case_formatting()
    {
        Config::set('app-settings.key_name_generator', 'camel_case');
        $entry = self::generateFakeEntry()->toArray();

        $this->assertSame('systemName.androidV10.multiWordsSuffixWithSpecialCharsAndSpace', $entry['key']);

    }

    public function test_it_generates_keys_with_snake_case_formatting()
    {
        Config::set('app-settings.key_name_generator', 'snake_case');
        $entry = self::generateFakeEntry()->toArray();

        $this->assertSame('system_name.android_v10.multi_words_suffix_with_special_chars_and_space', $entry['key']);
    }

    public function test_it_generates_keys_with_kebab_case_formatting()
    {
        Config::set('app-settings.key_name_generator', 'kebab-case');
        $entry = self::generateFakeEntry()->toArray();

        $this->assertSame('system-name.android-v10.multi-words-suffix-with-special-chars-and-space', $entry['key']);
    }

    public function test_it_generates_keys_from_string_with_dots()
    {
        Config::set('app-settings.key_name_generator', 'kebab-case');
        $entry = Entry::make('testValue', Type::INTEGER, 'Some description')
            ->appendKey('systemName.testing_environment');

        $this->assertSame('system-name.testing-environment', $entry->toArray()['key']);
    }

    public function test_it_generates_keys_from_array()
    {
        Config::set('app-settings.key_name_generator', 'kebab-case');
        $entry = Entry::make('testValue', Type::INTEGER, 'Some description')
            ->appendKey(['systemName', 'testing_environment']);

        $this->assertSame('system-name.testing-environment', $entry->toArray()['key']);
    }

    public function test_it_generates_keys_from_array_and_append_key()
    {
        Config::set('app-settings.key_name_generator', 'kebab-case');
        $entry = Entry::make('testValue', Type::INTEGER, 'Some description')
            ->appendKey(['systemName', 'testing_environment'])
            ->appendKey('has_testing');

        $this->assertSame('system-name.testing-environment.has-testing', $entry->toArray()['key']);
    }
}
