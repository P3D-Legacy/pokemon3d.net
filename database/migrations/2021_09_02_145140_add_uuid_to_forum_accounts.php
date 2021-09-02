<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUuidToForumAccounts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('forum_accounts', function (Blueprint $table) {
            $table->dropColumn('id');
        });
        // These needs to be run at two seperate times
        Schema::table('forum_accounts', function (Blueprint $table) {
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
        Schema::table('forum_accounts', function (Blueprint $table) {
            $table->id();
            $table->dropColumn('uuid');
        });
    }
}
