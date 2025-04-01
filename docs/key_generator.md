# Key generator convention

## Selecting preferred keys

In `config/app-settings.php` in section `key_name_generator` you can set key generator type.
Available are:

- `XGrz\Settings\Enums\KeyNaming::CAMEL_CASE`
- `XGrz\Settings\Enums\KeyNaming::SNAKE_CASE`
- `XGrz\Settings\Enums\KeyNaming::KEBAB_CASE`

*WARNING!*

You should configure this BEFORE first run on `php artisan settings:init` command.
When this config is changed after first run it can "duplicate" keys with selected convention.
Old keys will persist in database until you delete them manually.