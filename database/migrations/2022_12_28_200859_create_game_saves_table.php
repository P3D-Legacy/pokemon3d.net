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
        Schema::create('game_saves', function (Blueprint $table) {
            $table->uuid();
            $table->foreign('user_id')->references('id')->on('users');
            $table->string('apricorns');
            $table->string('berries');
            $table->string('box');
            $table->string('daycare');
            $table->string('halloffame');
            $table->string('itemdata');
            $table->string('items');
            $table->string('npc');
            $table->string('options');
            $table->string('party');
            $table->string('player');
            $table->string('pokedex');
            $table->string('register');
            $table->string('roamingpokemon');
            $table->string('secretbase');
            $table->string('statistics');
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
        Schema::dropIfExists('game_saves');
    }
};
