<?php

namespace XGrz\Settings\Actions;

use Illuminate\Support\Facades\File;
use XGrz\Settings\Exceptions\ConfigFileNotFoundException;

class GetRawDefinitions
{
    /**
     * This action returns raw array for defined settings
     * @return array
     * @throws ConfigFileNotFoundException
     */
    public static function make(): array
    {
        return File::exists(base_path('settings/definitions.php'))
            ? include base_path('settings/definitions.php')
            : throw new ConfigFileNotFoundException('Settings definitions file not found. Have you run `php artisan settings:publish`?');


    }
}