<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('characters_in_episodes', function (Blueprint $table) {
            $table->id();
            $table->string('character_slug');
            $table->string('episode_slug');

            $table->foreign('character_slug')->references('slug')->on('characters');
            $table->foreign('episode_slug')->references('slug')->on('episodes');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('characters_in_episodes');
    }
};
