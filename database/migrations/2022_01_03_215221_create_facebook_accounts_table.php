<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFacebookAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('facebook_accounts', function (Blueprint $table) {
            $table->increments('aid');
            $table->uuid('uuid')->unique();
            $table->bigInteger('id')->comment('Facebook ID');
            $table->text('name')->comment('Facebook Name');
            $table->text('email')->comment('Facebook Email');
            $table->text('avatar')->comment('Facebook Avatar URL');
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
        Schema::dropIfExists('facebook_accounts');
    }
}
