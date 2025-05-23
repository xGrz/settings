<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use XGrz\Settings\Helpers\Config\SettingsConfig;

return new class extends Migration {
    public function up(): void
    {
        Schema::create(SettingsConfig::getDatabaseTableName(), function(Blueprint $table) {
            $table->id();

            $table->string('key')->unique();

            $table->string('description')
                ->nullable();

            $table->integer('type');

            $table->text('value')
                ->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists(SettingsConfig::getDatabaseTableName());
    }
};
