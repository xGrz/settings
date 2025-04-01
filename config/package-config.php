<?php


use XGrz\Settings\Enums\KeyNaming;
use XGrz\Settings\Enums\Type;

return [
    /**
     * Database table name. Configure this before migration is fired
     */
    'database_table' => env('APPLICATION_SETTINGS_TABLE_NAME', 'app_settings'),

    /**
     * Cache configuration
     */
    'cache' => [
        /**
         * Set cache timeout for settings (in seconds)
         */
        'ttl' => env('APPLICATION_SETTINGS_CACHE_TTL', 86400),

        /** Cache key to store data. Change only when you have a conflict with other packages */
        'key' => env('APPLICATION_SETTINGS_CACHE_KEY', 'settings'),
    ],

    /**
     * Select key generation convention KeyNaming (enum) or string can be set
     * - camelCase // default
     * - snake_case
     * - kebab-case
     */
    'key_name_generator' => KeyNaming::CAMEL_CASE,

    /**
     * Definitions of settings
     */
    'definitions' => [
        'system' => [
            // define using SettingEntry helper
            'seller_address_name' => XGrz\Settings\Helpers\Entry::make()
                ->value('Laravel Corporation')
                ->description('Company address: name'),

            // prefixes are discovered as 'system', suffixes as 'seller_address_name'
            'seller_address_city' => XGrz\Settings\Helpers\Entry::make()
                ->description('Company address: city')
                ->value('Warsaw'),

            // make parameters works too
            'seller_address_street' => XGrz\Settings\Helpers\Entry::make(description: 'Company address: street and number', value: 'Willow Street 2002'),
        ],

        'pageLength' => [
            // creates 'pageLength.default' setting with integer data type and initial value = 10 without description.
            'default' => 10,
        ],
        'pageWidth' => [
            // direct attach setting props [setting_type, description, value]
            'user_defaults' => [
                'description' => 'Page width description',
                'value' => 1024,
            ],
        ],
        // top level setting entry helper. Prefix and suffix are required.
        XGrz\Settings\Helpers\Entry::make()
            ->value('Laravel settings package')
            ->description('Some system name')
            ->prefix('system')
            ->suffix('name')
            ->settingType(Type::INTEGER),
        XGrz\Settings\Helpers\Entry::make()
            ->prefix('system')
            ->suffix('yes_no')
            ->settingType(Type::YES_NO),
    ],

];
