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
        Schema::create('gamejolt_account_trophies', function (Blueprint $table) {
            $table->increments('aid');
            $table->uuid('uuid')->unique();
            $table->unsignedBigInteger('id')->comment('Game Jolt Trophy ID');
            $table->string('title')->comment('Game Jolt Trophy Title');
            $table->string('difficulty')->comment('Game Jolt Trophy Difficulty');
            $table->string('description')->comment('Game Jolt Trophy Description');
            $table->text('image_url')->comment('Game Jolt Trophy Image URL');
            $table
                ->boolean('achieved')
                ->default(false)
                ->comment('Game Jolt Trophy Achieved By User');
            $table->unsignedBigInteger('gamejolt_account_id')->comment('Game Jolt Account ID');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gamejolt_account_trophies');
    }
};
