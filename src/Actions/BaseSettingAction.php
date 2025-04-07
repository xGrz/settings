<?php

namespace XGrz\Settings\Actions;

use Illuminate\Support\Collection;
use Illuminate\Support\Sleep;
use Laravel\Prompts\Progress;
use XGrz\Settings\Enums\Operation;
use XGrz\Settings\Exceptions\ConfigFileNotFoundException;
use XGrz\Settings\Helpers\Builder\GetSettingsDefinitions;
use XGrz\Settings\Helpers\Builder\GetStoredSettings;
use XGrz\Settings\Helpers\Builder\SettingItemsBuilder;
use XGrz\Settings\Interfaces\SettingActionInterface;
use XGrz\Settings\ValueObjects\SettingItem;
use function Laravel\Prompts\progress;

abstract class BaseSettingAction implements SettingActionInterface
{
    protected ?Operation $operation = NULL;

    protected readonly Collection $settings;

    /**
     * @throws ConfigFileNotFoundException
     */
    final private function __construct(?array $definitions = NULL)
    {
        $this->settings = SettingItemsBuilder::make(
            GetSettingsDefinitions::asCollection($definitions ?? GetSettingsDefinitions::raw()),
            GetStoredSettings::asCollection()
        );
    }

    /**
     * @throws ConfigFileNotFoundException
     */
    final public static function make(?array $definitions = NULL): static
    {
        return new static($definitions);
    }

    protected function getSelectedSettings(): Collection
    {
        if ($this->operation === NULL) {
            return $this->settings;
        }

        return $this->settings->filter(fn(SettingItem $setting) => $setting->operation === $this->operation);
    }

    public function execute(): int
    {
        if ($this->getSelectedSettings()->isEmpty()) {
            return 0;
        }

        $counter = 0;
        foreach ($this->getSelectedSettings() as $item) {
            $item->store();
            $counter++;
        }

        return $counter;
    }

    public function executeWithProgress(): Progress|array
    {
        if ($this->getSelectedSettings()->isEmpty()) {
            return [];
        }

        return progress(
            label: $this->operation->name ?? 'Processing settings sync',
            steps: $this->getSelectedSettings(),
            callback: function($setting, $progress) {
                Sleep::for(100)->millisecond();
                $progress
                    ->label('Processing ' . $setting->key)
                    ->hint('Processed ' . $setting->key);

                return $setting->store();
            }
        );
    }

    public function getTableHeading(): array
    {
        return ['Key', 'Description', 'DefinedType', 'StoredType', 'Operation'];
    }

    public function getTableBody(): Collection
    {
        return $this->getSelectedSettings()->map(fn(SettingItem $setting) => [
            'key' => $setting->key,
            'description' => $setting->definedDescription ?? $setting->storedDescription ?? '<fg=gray>Not defined</>',
            'definedType' => $setting->definedType->name ?? '<fg=gray>---</>',
            'storedType' => $setting->storedType->name ?? '<fg=gray>---</>',
            'operation' => $setting->operation->commandLineLabel(),
        ]);
    }
}
