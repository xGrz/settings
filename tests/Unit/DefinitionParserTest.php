<?php

namespace XGrz\Settings\Tests\Unit;

use Illuminate\Support\Facades\Config;
use XGrz\Settings\Actions\ParseDefinitions;
use XGrz\Settings\Exceptions\UnresolvableValueTypeException;
use XGrz\Settings\Helpers\Entry;
use XGrz\Settings\Tests\TestCase;

class DefinitionParserTest extends TestCase
{
    public function test_can_parse_defined_settings()
    {
        $definitions = ParseDefinitions::make()->toArray();

        $this->assertCount(8, $definitions);
    }

    public function test_throws_exception_when_type_is_unresolvable()
    {
        $config = Config::get('app-settings.definitions');
        $config['test'] = Entry::make();
        Config::set('app-settings.definitions', $config);

        $this->expectException(UnresolvableValueTypeException::class);
        ParseDefinitions::make();
    }
}