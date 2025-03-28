<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use xGrz\Settings\Helpers\SettingsConfig;

return new class extends Migration {


    public function up(): void
    {
        Schema::create(SettingsConfig::getDatabaseTableName(), function (Blueprint $table) {
            $table->id();

            $table->string('prefix'); // slug prefix

            $table->string('suffix'); // slug suffix


            $table->string('description')
                ->nullable();

            $table->integer('setting_type');

            $table->text('value')
                ->nullable();

            $table->string('context')->nullable();

            $table->timestamps();
        });

        if (DB::getDriverName() === 'sqlite') {
            DB::statement('ALTER TABLE ' . SettingsConfig::getDatabaseTableName() . ' ADD COLUMN key TEXT GENERATED ALWAYS AS (prefix || "." || suffix) VIRTUAL');
        } else {
            Schema::table(SettingsConfig::getDatabaseTableName(), function (Blueprint $table) {
                $table->string('key')
                    ->after('suffix')
                    ->virtualAs("CONCAT(`prefix`, '.', `suffix`)")
                    ->unique();
            });
        }

    }


    public function down(): void
    {
        Schema::dropIfExists(SettingsConfig::getDatabaseTableName());
    }
};
