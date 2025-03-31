![Version](https://img.shields.io/github/v/tag/xgrz/settings?label=Release&color=stone&sort=semver)
[![Tests](https://github.com/xGrz/settings/actions/workflows/tests.yml/badge.svg?branch=main)](https://github.com/xGrz/settings/actions/workflows/tests.yml)
[![Coverage](https://github.com/xGrz/settings/actions/workflows/coverage.yml/badge.svg)](https://github.com/xGrz/settings/actions/workflows/coverage.yml)
![L10](https://img.shields.io/badge/Laravel-v10.x-blue)
![L11](https://img.shields.io/badge/Laravel-v11.x-blue)
![L12](https://img.shields.io/badge/Laravel-v12.x-blue)

# *Settings* Package for Laravel

## Compatibility

This package has been tested with:

- Laravel 10.x, 11.x, 12.x
- PHP 8.1, 8.2, 8.3, 8.4 (PHP <8.1 is not supported)
- MySQL, SQLite databases

Please note that for performance reasons, this package uses caching. It is recommended to use Redis or a file-based
cache system instead of a database cache.

## Installation

1. Install the package from Packagist and publish the configuration:
    ```
    composer require xgrz/settings
    php artisan settings:publish
    ```

2. Edit the configuration file in your `config/app-settings` folder:
    - Customize your database table name
    - Set the cache key
    - Define the cache timeout

3. The last key in the `app-settings` config file is `initial`. This is very useful during development. You can
   predefine setting keys and values and inject them into every application instance ([see details](docs/settings.md))

4. After customizing your configuration file, run the migration:
   ```
   php artisan migrate
   ```
   **Note:** Once the migration is completed, the `database_table` value in `config/app-settings` cannot be changed.
   Modifying it will break your application.

5. Once your configuration is complete, initialize your settings with:
   ```
   php artisan settings:init
   ```

## Usage

You have two simple ways to retrieve your configuration values:

### Using the Facade

```php
try {
    XGrz\Settings\Facades\Settings::get($keyName);
} catch (XGrz\Settings\Exceptions\SettingKeyNotFoundException $e) {
    // Setting not found
}
```  

### Using the Global Helper

```php
settings($keyName);
```

If the key is missing when using the global helper, an exception will be thrown as mentioned above. However, you can
pass a second parameter to the `settings` helper as a default value. In this case, no exception will be thrown, and the
default value will be returned.

```php
settings($keyName, $defaultValue);
```

## Warnings

#### Avoid changing values at database level.

Changes made that way will be not cached until you will flush app-cache or use:

```php
    XGrz\Settings\Facades\Settings::invalidateCache();
    // or 
    XGrz\Settings\Facades\Settings::refreshCache();
```

Preferred method for any changes is using model `XGrz\Settings\Models\Setting::class`.

#### Key generation

Prefix and suffix of each key are joined by dot (.) and creates `key`. Key is virtually generated at database level and
can be changed only by editing prefix or suffix.
Prefix and suffix are internally formatted as camel case (for ex. systemName).

#### Changing setting type

You can safely change setting type in very limited way (for data integrity reasons).
Allowed type changes:

- ```SettingType::ON_OFF``` to ```SettingType::YES_NO```
- ```SettingType::YES_NO``` to ```SettingType::ON_OFF```
- ```SettingType::INTEGER``` to ```SettingType::FLOAT``` (cannot change-back to integer)
- ```SettingType::STRING``` to ```SettingType::TEXT``` (cannot change-back to string)

You can delete key and create new one if you need to change setting_type to other types. 

