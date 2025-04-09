# Initial settings

Settings are defined in `settings\definitions.php` in main project directory.

## Define key

Example of defining 2 keys:

```php

use \XGrz\Settings\ValueObjects\Entry;
use \XGrz\Settings\Enums\Type;

return [
    'prefix1' => [
        'suffix1' => Entry::make(123)->type(Type::INTEGER),
        'suffix2' => Entry::make(true)->type(Type::ON_OFF),
    ],    
    'prefix2' => [
        'suffix1' => Entry::make(123)->type(Type::FLOAT),
        'suffix2' => Entry::make('abc')->description('Description')->type(Type::TEXT),
    ],    
]
```

`Entry` is a basic definition for each setting entry.
You can pass `value`, `type` and `description`. One of (value or type) is required. If your value is null type is
required. Entry can detect value type, however it is recommended to set it manually (avoid mistakes). For example:

| Passed Value             | Detected type |
|--------------------------|---------------|
| 123                      | Type::INTEGER |
| 123.0                    | Type:FLOAT    |
| '123'                    | Type:STRING   |
| '123.0'                  | Type:STRING   |
| true                     | Type::YES_NO  |
| false                    | Type::YES_NO  |
| null                     | *Undetected*  |
| string (up to 200 chars) | Type::STRING  |
| string (200+ chars)      | Type::TEXT    |



