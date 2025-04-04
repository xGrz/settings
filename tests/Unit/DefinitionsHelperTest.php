<?php

namespace XGrz\Settings\Tests\Unit;

use Exception;
use File;
use Illuminate\Support\Collection;
use XGrz\Settings\Helpers\DefinitionsHelper;
use XGrz\Settings\Tests\TestCase;

class DefinitionsHelperTest extends TestCase
{
    public function test_throws_exception_when_definitions_file_is_missing()
    {
        File::delete(base_path('settings/definitions.php'));
        $this->expectException(Exception::class);

        $helper = new DefinitionsHelper;
    }

    public function test_can_publish_definitions_by_calling_the_command()
    {
        $this->artisan('settings:publish-config');
        $this->assertFileExists(base_path('settings/definitions.php'));

        $definitions = include base_path('settings/definitions.php');
        $this->assertIsArray($definitions);
    }

    public function test_helper_can_list_definitions()
    {
        $helper = new DefinitionsHelper;
        $this->assertInstanceOf(Collection::class, $helper->toListing());
        $this->assertGreaterThanOrEqual(2, $helper->toListing()->count());
    }

    public function test_helper_can_list_definitions_heading()
    {
        $helper = new DefinitionsHelper;
        $this->assertGreaterThan(2, $helper->heading());
    }


}