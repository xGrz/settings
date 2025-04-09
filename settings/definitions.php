<?php

use XGrz\Settings\ValueObjects\Entry;

/**
 * After changing, adding or removing definitions, remember to run the command:
 *
 * `php artisan settings:sync`
 *
 * Consider adding Type (`XGrz\Settings\Enums\Type`) to each Entry
 */

return [
    'system' => [
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
        'show_email' => Entry::make(false),
        'show_phone' => Entry::make(true),
    ],
    'pageLength' => [
        'default' => Entry::make(value: 10),
    ],
    'pageWidth' => [
        'user_defaults' => Entry::make(value: 1024, description: 'Page width description'),
        'global_defaults' => Entry::make(value: 2048),
    ],
];
