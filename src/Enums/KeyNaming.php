<?php

namespace XGrz\Settings\Enums;

enum KeyNaming: string
{
    case CAMEL_CASE = 'camelCase';
    case SNAKE_CASE = 'snake_case';
    case KEBAB_CASE = 'kebab-case';
}
