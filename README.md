![Version](https://img.shields.io/github/v/tag/xgrz/settings?label=Release&color=stone&sort=semver)
[![Tests](https://github.com/xGrz/settings/actions/workflows/tests.yml/badge.svg?branch=main)](https://github.com/xGrz/settings/actions/workflows/tests.yml)
[![Coverage](https://github.com/xGrz/settings/actions/workflows/coverage.yml/badge.svg)](https://github.com/xGrz/settings/actions/workflows/coverage.yml)
![L10](https://img.shields.io/badge/Laravel-v10.x-blue)
![L11](https://img.shields.io/badge/Laravel-v11.x-blue)
![L12](https://img.shields.io/badge/Laravel-v12.x-blue)

# *Settings* Package for Laravel

The Settings package for Laravel was created to facilitate easy management of global application settings in both
development and production environments. Here are the main functions and features of the package:

## Key Features

1. **Application Settings Management** - the package allows storing and easy management of global application
   configuration
2. **File-Based Configuration** - definition of keys, types, and default values takes place in a configuration file
3. **Type Validation** - the package ensures proper data types are maintained (as defined in config)
4. **Performance** - utilization of caching mechanism (Redis or File recommended) to limit database queries
5. **Naming Standardization** - automatic formatting of keys according to the chosen convention (camel case, kebab case,
   or snake case)
6. **Nested keys** - `prefix.suffix` naming convention for all settings keys allows store structured data.

## Installation

1. Install the package from Packagist and publish the configuration:
    ```
    composer require xgrz/settings
    php artisan settings:publish-config
    php artisan settings:publish-migration
    ```

2. Edit the configuration file in your `config/app-settings` folder:
    - Customize your database table name
    - Set the cache key
    - Select [key generator convention](docs/key_generator.md)
    - Define the cache timeout

3. After customizing your configuration file, run the migration:
   ```
   php artisan migrate
   ```
   **Note:** Once the migration is completed, the `database_table` value in `config/app-settings` cannot be changed.
   Modifying it can break your application.

4. Define your settings in `settings/definitions.php` file (in main app directory) [details](docs/definitions.md).

5. Once your configuration is complete, initialize your settings with:
   ```
   php artisan settings:sync
   ```

## Usage

You have two simple ways to retrieve your configuration values:

### Using the Facade

```php
try {
    XGrz\Settings\Facades\Settings::get($keyName);
} catch (XGrz\Settings\Exceptions\SettingKeyNotFoundException $e) {
    // Setting not found exception
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
settings(stirng $keyName, mixed $defaultValue);
```

### Retrieving an entire branch of settings

If you want to retrieve an entire branch of settings for a specific key, you must use a dot at the end. For example, to
retrieve the "system" branch, use:

```php
settings('system.');
``` 

If there are keys named `system.abc` and `system.bca`, an array will be returned:

```
array(
   'abc' => 'value1',
   'bca' => 'value2',
);
```

## CI/CD deployments

In your deployment script you should consider to add `php artisan settings:sync --silent` for safe settings sync, or
`php artisan settings:sync --silent --force` if you want to force sync settings (test your app first!)

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

Definition file is flattened with dot naming convention. Partial key names are formatted with defined generator type in
your `config/app-settings.php` (_camel case_ | kebab case | snake case).

If you decide to change key name generator type you can reformat your keys by using artisan console command:

```
   php artisan settings:format-keys
```

This command will walk through your config and apply new key naming type.

#### Changing setting type

You can safely change setting type in very limited way (for data integrity reasons).
Allowed type changes:

- ```SettingType::ON_OFF``` to ```SettingType::YES_NO```
- ```SettingType::YES_NO``` to ```SettingType::ON_OFF```
- ```SettingType::INTEGER``` to ```SettingType::FLOAT``` (cannot change-back to integer)
- ```SettingType::STRING``` to ```SettingType::TEXT``` (cannot change-back to string)

You can delete key and create new one if you need to change setting_type to other types.

## Compatibility

This package has been tested with:

- Laravel 10.x, 11.x, 12.x
- PHP 8.1-8.4
- MySQL, SQLite databases

Please note that for performance reasons, this package uses caching. It is recommended to use Redis or a file-based
cache system instead of a database cache.
