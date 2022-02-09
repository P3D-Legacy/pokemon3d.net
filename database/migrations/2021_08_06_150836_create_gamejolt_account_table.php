<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('game_jolt_accounts', function (Blueprint $table) {
            $table->increments('aid');
            $table->uuid('uuid')->unique();
            $table
                ->bigInteger('id')
                ->unsigned()
                ->unique()
                ->comment('GameJolt Account ID');
            $table->text('username')->comment('GameJolt Username');
            $table->string('token')->comment('GameJolt Token');
            $table->timestamp('verified_at')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('game_jolt_accounts');
    }
};
