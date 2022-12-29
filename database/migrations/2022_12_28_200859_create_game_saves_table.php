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
            $table->bigInteger('user_id');
            $table->longText('apricorns');
            $table->longText('berries');
            $table->longText('box');
            $table->longText('daycare');
            $table->longText('halloffame');
            $table->longText('itemdata');
            $table->longText('items');
            $table->longText('npc');
            $table->longText('options');
            $table->longText('party');
            $table->longText('player');
            $table->longText('pokedex');
            $table->longText('register');
            $table->longText('roamingpokemon');
            $table->longText('secretbase');
            $table->longText('statistics');
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
