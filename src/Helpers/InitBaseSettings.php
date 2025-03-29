<?php

namespace XGrz\Settings\Helpers;

use Illuminate\Database\UniqueConstraintViolationException;
use XGrz\Settings\Models\Setting;

class InitBaseSettings
{

    private array $baseConfig = [];

    /**
     * @var array<int, SettingEntry>
     */
    private array $settings = [];

    public function __construct()
    {
        $this->baseConfig = config('app-settings-definitions');
        $this->processBaseConfig();
        $this->init();
    }

    public static function make(): InitBaseSettings
    {
        return new self();
    }

    private function processBaseConfig(): void
    {
        foreach ($this->baseConfig as $prefix => $suffixDefinition) {
            if ($suffixDefinition instanceof SettingEntry) {
                $this->settings[] = $suffixDefinition;
            }
            if (is_array($suffixDefinition)) {
                foreach ($suffixDefinition as $suffix => $entryDefinition) {

                    if ($entryDefinition instanceof SettingEntry) {
                        $entryDefinition
                            ->suffix($suffix)
                            ->prefix($prefix);
                        $this->settings[] = $entryDefinition;
                    } elseif (is_array($entryDefinition)) {
                        $entry = SettingEntry::make(prefix: $prefix, suffix: $suffix)->fill($entryDefinition);
                        $this->settings[] = $entry;
                    } else {
                        $this->settings[] = SettingEntry::make(prefix: $prefix, suffix: $suffix, value: $entryDefinition);
                    }

                }
            }
        }
    }

    public function getDefinedSettings(): array
    {
        return $this->settings;
    }

    private function init(): void
    {
        foreach ($this->getDefinedSettings() as $setting) {
            try {
                Setting::create($setting->getDefinition());
            } catch (UniqueConstraintViolationException $exception) {

            }
        }
    }
}
