<?php

namespace XGrz\Settings\Actions;

use XGrz\Settings\Helpers\Entry;

class ParseDefinitions
{
    private array $definitions = [];

    public static function make(): ParseDefinitions
    {
        return new self();
    }

    private function __construct()
    {
        $this->getConfigDefinitions(config('app-settings.definitions', []));
        $this->fillValueTypes();
    }

    private function getConfigDefinitions(array|Entry $branch, null|string|array $branchKey = null): void
    {
        if ($branch instanceof Entry) {
            $branch->appendKey($branchKey);
            $this->definitions[] = $branch;
        } else {
            foreach ($branch as $key => $value) {
                $this->getConfigDefinitions($value, $key);
            }
        }
    }

    private function fillValueTypes(): void
    {
        foreach ($this->definitions as $key => $definitionEntry) {
            $definitionEntry->detectType();
            $this->definitions[$key] = $definitionEntry;
        }
    }

    public function getDefinitions(): array
    {
        return $this->definitions;
    }

    public function toArray(): array
    {
        return collect($this->definitions)->map(fn(Entry $entry) => $entry->toArray())->toArray();
    }

}