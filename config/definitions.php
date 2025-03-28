<?php

/**
 * Each time you change/add/remove any element of array remember to use console:
 * php artisan laravel-app-settings:sync
 */

use xGrz\Settings\Helpers\SettingEntry as Entry;

return [
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
        ->settingType(\xGrz\Settings\Enums\SettingType::INTEGER),
    Entry::make()
        ->prefix('system')
        ->suffix('yes_no')
        ->settingType(\xGrz\Settings\Enums\SettingType::YES_NO),
];
