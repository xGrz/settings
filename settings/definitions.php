<?php

use XGrz\Settings\Enums\Type;
use XGrz\Settings\ValueObjects\Entry;

return [
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
            ],
        ],
    ],
    'pageLength' => [
        'default' => Entry::make(value: 10)->type(Type::INTEGER),
    ],
    'pageWidth' => [
        'user_defaults' => Entry::make(value: 1024, description: 'Page width description'),
        'global_defaults' => Entry::make(value: 2048),
    ],
];
