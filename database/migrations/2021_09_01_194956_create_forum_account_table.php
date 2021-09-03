<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateForumAccountTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('forum_accounts', function (Blueprint $table) {
            $table->increments('aid');
            $table->uuid('uuid')->unique();
            $table->text('username')->comment('Forum Username');
            $table->string('password')->comment('Forum Password');
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
        Schema::dropIfExists('forum_accounts');
    }
}
