<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUuidToGameJoltAccounts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('game_jolt_accounts', function (Blueprint $table) {
            $table->dropPrimary('id');
            $table->uuid('uuid')->primary()->first();
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
            $table->dropColumn('uuid');
        });
    }
}
