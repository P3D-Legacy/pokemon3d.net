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
        Schema::create('pokemon_species', function (Blueprint $table) {
            $table->id();
            $table->string('game_id')->unique();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('type_1')->nullable();
            $table->string('type_2')->nullable();
            $table->unsignedSmallInteger('hp')->nullable();
            $table->unsignedSmallInteger('attack')->nullable();
            $table->unsignedSmallInteger('defense')->nullable();
            $table->unsignedSmallInteger('special_attack')->nullable();
            $table->unsignedSmallInteger('special_defense')->nullable();
            $table->unsignedSmallInteger('speed')->nullable();
            $table->string('ability_1')->nullable();
            $table->string('ability_2')->nullable();
            $table->string('hidden_ability')->nullable();
            $table->decimal('height', 5, 2)->nullable();
            $table->decimal('weight', 6, 2)->nullable();
            $table->string('species')->nullable();
            $table->text('pokedex_entry')->nullable();
            $table->json('moves')->nullable();
            $table->json('egg_moves')->nullable();
            $table->json('machines')->nullable();
            $table->json('evolution')->nullable();
            $table->longText('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pokemon_species');
    }
};
