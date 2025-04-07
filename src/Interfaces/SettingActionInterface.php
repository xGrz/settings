<?php

namespace XGrz\Settings\Interfaces;

use Illuminate\Support\Collection;
use Laravel\Prompts\Progress;

interface SettingActionInterface
{
    public static function make(?array $definitions = null): static;

    public function execute(): int;

    public function executeWithProgress(): Progress|array;

    public function getTableBody(): Collection;
}
