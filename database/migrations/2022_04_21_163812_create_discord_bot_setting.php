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
        Schema::create('discord_bot_settings', function (Blueprint $table) {
            $table->id();
            $table
                ->bigInteger('category_id')
                ->unsigned()
                ->default(0);
            $table
                ->bigInteger('chat_id')
                ->unsigned()
                ->default(0);
            $table
                ->bigInteger('events_id')
                ->unsigned()
                ->default(0);
            $table->json('hide_events')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('discord_bot_settings');
    }
};
