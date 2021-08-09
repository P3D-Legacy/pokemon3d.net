<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGamejoltAccountTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('game_jolt_accounts', function (Blueprint $table) {
            $table->bigInteger('id')->unsigned()->primary()->unique()->first()->nullable()->comment('GameJolt Account ID');
            $table->text('username')->nullable()->comment('GameJolt Username');
            $table->string('token')->nullable()->comment('GameJolt Token');
            $table->timestamp('verified_at')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->timestamps();
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
}
