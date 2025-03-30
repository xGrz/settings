<?php

use XGrz\Settings\Enums\SettingType;
use XGrz\Settings\Helpers\SettingEntry as Entry;

return [
    /*
     * Database table name. Configure this before migration is fired
     */
    'database_table' => 'application_settings',

    /*
     * Cache configuration
     */
    'cache' => [
        /*
         * Set cache timeout for settings (in seconds)
         * You can set false to disable cache
         */
        'ttl' => 10,

        /* Cache key to store data. Change only when you have a conflict with other modules */
        'key' => 'app-settings-test'
    ],

    'initial' => [
        'system' => [
            'seller_address_name' => Entry::make()
                ->value('Laravel Corporation')
                ->description('Seller name'),

            'seller_address_city' => Entry::make()
                ->value('Warsaw'),

            'seller_address_postal_code' => Entry::make(value: '00-950'),

            'seller_address_street' => Entry::make(value: '1st Street'),
            'seller_address_number' => Entry::make(value: '200/1'),
        ],
        'pageLength' => [
            'default' => '10',
        ],
        'pageWidth' => [
            'user_defaults' => [
                'description' => 'Page width description',
                'value' => 1024,
            ],
        ],
        Entry::make()
            ->value('12')
            ->description('Page length2')
            ->context('settings')
            ->prefix('system')
            ->suffix('name')
            ->settingType(SettingType::INTEGER),
        Entry::make()
            ->prefix('system')
            ->suffix('yes_no')
            ->settingType(SettingType::YES_NO),
    ],

];
