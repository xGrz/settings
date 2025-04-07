<?php

namespace XGrz\Settings\Console\Commands;

use Illuminate\Console\Command;
use XGrz\Settings\Models\Setting;
use function Laravel\Prompts\search;

class SettingShowCommand extends Command
{
    protected $signature = 'settings:show';

    protected $description = 'Command description';

    public function handle(): void
    {

        Setting::orderBy('key')->get()->pluck('key', 'id')->toArray();

        $selectedSetting = search(
            'Select a setting to view details:',
            fn(string $value) => Setting::where('key', 'LIKE', "%{$value}%")->orderBy('key')->pluck('key', 'id')->all(),
            scroll: 10,
            required: true,
        );
        $setting = Setting::find($selectedSetting);
        $this->line('<fg=green>Showing details for setting:</>');
        $this->components->twoColumnDetail('Key name', '<fg=bright-green>' . $setting->key . '</>');
        $this->components->twoColumnDetail('Type', '<fg=yellow>' . $setting->type->name . '</>');
        $this->components->twoColumnDetail('Value', self::formatValue($setting->value));
        $this->components->twoColumnDetail('Description', self::formatDescription($setting->description));
        $this->components->twoColumnDetail('Created at', '<fg=bright-blue>' . $setting->created_at->format('Y-m-d H:i:s') . '</>');
        $this->components->twoColumnDetail('Updated at', '<fg=bright-blue>' . $setting->updated_at->format('Y-m-d H:i:s') . '</>');
    }

    private function formatValue(mixed $value): string
    {
        if (empty($value)) {
            return '<fg=gray>empty</>';
        }
        if (is_bool($value)) {
            return $value ? '<fg=green>true</>' : '<fg=red>false</>';
        }
        if (is_int($value) || is_float($value)) {
            return '<fg=green>' . $value . '</>';
        }
        return str($value)
            ->limit(40)
            ->prepend('<fg=bright-cyan>')
            ->append('</>')
            ->toString();
    }

    private function formatDescription(?string $description = 'No description provided'): string
    {
        return str($description)
            ->limit(40)
            ->prepend('<fg=gray>')
            ->append('</>')
            ->toString();
    }
}
