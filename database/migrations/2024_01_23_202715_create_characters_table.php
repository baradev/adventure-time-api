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
        Schema::create('characters', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('name');
            $table->string('full_name');
            $table->text('description');
            $table->string('specie');
            $table->text('quotes')->nullable();
            $table->text('image');
            $table->text('thumbnail');
            $table->string('kingdom_slug');
            $table->foreign('kingdom_slug')->references('slug')->on('kingdoms');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('characters');
    }
};
