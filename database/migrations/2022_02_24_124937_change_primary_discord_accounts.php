<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangePrimaryDiscordAccounts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $duplicateRecords = DB::table('discord_accounts')->selectRaw('id, count(`id`) as `occurences`')->groupBy('id')->having('occurences', '>', 1)->get();
        foreach ($duplicateRecords as $duplicateRecord) {
            DB::table('discord_accounts')->where('id', $duplicateRecord->id)->whereNotNull('deleted_at')->delete();
        }
        Schema::table('discord_accounts', function (Blueprint $table) {
            $table->dropColumn('aid');
            $table->primary('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
