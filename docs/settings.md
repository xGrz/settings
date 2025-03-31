# Initial settings

Initial settings are defined in `config\app-settings.php` under `initial` key.

## Define key

Example of defining 2 keys:

```php
`initial` => [
    'prefix1' => [
        'suffix1' => [
            'setting_type' => XGrz\Settings\Enums\SettingType::INTEGER,
            'value' => 2,
            'description' => 'Description for key prefix1.suffix1'
        ],
        'suffix2' => [
            'setting_type' => XGrz\Settings\Enums\SettingType::YES_NO,
            'value' => true,
            'description' => 'Description for key prefix1.suffix2'
        ],
    ],    
]
```

You should always set setting_type, however settings-package can recognize value type and set setting_type behind scene.
This feature is not ideal so be careful with this.

### Quick define keys without descriptions with auto recognize key types

```php
`initial` => [
    'prefix1' => [
        'suffix1' => 2,
        'suffix2' => true,
    ],    
]
```

In that case two keys will be generated:

- `prefix1.suffix1` with type `SettingType::INTEGER`
- `prefix1.suffix2` with type `SettingType::ON_OFF`

### Define using helper

Fell free to use `XGrz\Settings\Helpers\SettingEntry::make()` helper.
You can pass it at initial, prefixes or suffixed level of `initial` key.

When you pass `SettingEntry::make()` helper at top level you have to define prefix, suffix and value or setting_type (
required).
Optionally you can pass `description` and `context`.

Example:

```php
`initial` => [
    XGrz\Settings\Helpers\SettingEntry::make()
        ->value('12')
        ->description('Page length2')
        ->context('settings')
        ->prefix('system')
        ->suffix('name')
        ->settingType(SettingType::INTEGER),
        
    XGrz\Settings\Helpers\SettingEntry::make()
        ->prefix('system')
        ->suffix('seo_name')
        ->settingType(SettingType::YES_NO),
]
```