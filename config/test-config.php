<?php

use XGrz\Settings\Enums\SettingType;
use XGrz\Settings\Helpers\SettingEntry as Entry;

return [
    'database_table' => 'application_settings',

    'cache' => [
        'ttl' => 5,

        'key' => 'app-settings-test',
    ],

    'key_name_generator' => 'camel',

    'definitions' => [
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
            ->prefix('system')
            ->suffix('name')
            ->settingType(SettingType::INTEGER),
        Entry::make()
            ->prefix('system')
            ->suffix('yes_no')
            ->settingType(SettingType::YES_NO),
    ],

];
