<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create(config('rinvex.settings.tables.settings'), function (Blueprint $table) {
            // Columns
            $table->increments('id');
            $table->string('key');
            $table->string('type');
            $table->string('value')->nullable();
            $table->json('options')->nullable();
            $table->json('name');
            $table->json('description')->nullable();
            $table->boolean('override_config')->default(0);
            $table->mediumInteger('sort_order')->unsigned()->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::drop(config('rinvex.settings.tables.settings'));
    }
}
