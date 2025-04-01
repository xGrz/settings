<?php

namespace XGrz\Settings\Helpers;

use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Support\Facades\Log;
use Throwable;
use XGrz\Settings\Exceptions\DetectValueTypeException;
use XGrz\Settings\Exceptions\DuplicatedKeyException;
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
        $this->getInitialConfig();
        $this->processBaseConfig();
        $this->init();
    }

    private function getInitialConfig(): void
    {
        $this->baseConfig = config('app-settings.initial', []);
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

    /**
     * @throws DuplicatedKeyException
     * @throws DetectValueTypeException
     */
    private function init(): void
    {
        foreach ($this->getDefinedSettings() as $setting) {
            try {
                Setting::create($setting->getDefinition());
            } catch (UniqueConstraintViolationException $exception) {
                Log::debug($setting->getDefinition()['prefix'] . '.' . $setting->getDefinition()['suffix'] . ' already exists. No action performed.', $setting->getDefinition());
                continue;
            } catch (Throwable $exception) {
                Log::debug($setting->getDefinition()['prefix'] . '.' . $setting->getDefinition()['suffix'] . ' key not created. See log for details.', $setting->getDefinition());
            }
        }
    }
}
