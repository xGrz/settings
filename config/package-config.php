<?php

use XGrz\Settings\Enums\KeyNaming;

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

];
