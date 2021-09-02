<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAidToGameJoltAccounts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('game_jolt_accounts', function (Blueprint $table) {
            $table->dropPrimary('uuid');
        });
        // These needs to be run at two seperate times
        Schema::table('game_jolt_accounts', function (Blueprint $table) {
            $table->increments('aid')->first();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('game_jolt_accounts', function (Blueprint $table) {
            $table->dropColumn('aid');
        });
    }
}
