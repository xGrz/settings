<?php

use XGrz\Settings\Helpers\Entry;

return [
    'database_table' => 'application_settings',

    'cache' => [
        'ttl' => 5,

        'key' => 'app-settings-test',
    ],

    'key_name_generator' => 'camel',

    'definitions' => [
        'system' => [
            'seller' => [
                'address' => [
                    'name' => Entry::make(value: 'Laravel Corporation')
                        ->description('Seller name'),
                    'city' => Entry::make(value: 'Warsaw'),
                    'street' => Entry::make(value: '1st Street'),
                ],
                'contact' => [
                    'phone' => Entry::make(value: '123456789'),
                    'email' => Entry::make(value: 'example@example.com'),
                ]
            ],
        ],
        'pageLength' => [
            'default' => Entry::make(value: 10),
        ],
        'pageWidth' => [
            'user_defaults' => Entry::make(value: 1024, description: 'Page width description'),
            'global_defaults' => Entry::make(value: 2048),
        ],
    ],
];
