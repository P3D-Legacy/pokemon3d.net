<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->dropColumn('uuid');
        });
    }
}
