<?php

namespace XGrz\Settings\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;
use XGrz\Settings\Enums\SettingType;
use XGrz\Settings\Models\Setting;

class SettingFactory extends Factory
{
    protected $model = Setting::class;

    public function definition(): array
    {
        return [
            'prefix' => str($this->faker->word())->camel()->toString(),
            'suffix' => str($this->faker->word())->camel()->toString(),
            'description' => $this->faker->text(),
            'setting_type' => fake()->randomElements(SettingType::class),
            'value' => $this->faker->word(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
