<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->uuid('uuid')->primary()->default(DB::raw('(UUID())'))->first();
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
