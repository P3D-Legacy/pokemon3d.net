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
        Schema::create('game_jolt_accounts', function (Blueprint $table) {
            $table->increments('aid');
            $table->uuid('uuid')->unique();
            $table
                ->bigInteger('id')
                ->unsigned()
                ->unique()
                ->comment('Game Jolt Account ID');
            $table->text('username')->comment('Game Jolt Username');
            $table->string('token')->comment('Game Jolt Token');
            $table->timestamp('verified_at')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::drop('game_jolt_accounts');
    }
};
