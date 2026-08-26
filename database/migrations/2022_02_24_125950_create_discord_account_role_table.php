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
        Schema::create('discord_account_discord_role', function (Blueprint $table) {
            $table->bigInteger('discord_account_id');
            $table
                ->foreign('discord_account_id')
                ->references('id')
                ->on('discord_accounts')
                ->onDelete('cascade');
            $table->bigInteger('discord_role_id');
            $table
                ->foreign('discord_role_id')
                ->references('id')
                ->on('discord_roles')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('discord_account_discord_role');
    }
};
