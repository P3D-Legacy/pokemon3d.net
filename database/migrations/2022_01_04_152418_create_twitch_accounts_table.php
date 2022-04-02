<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('twitch_accounts', function (Blueprint $table) {
            $table->increments('aid');
            $table->uuid('uuid')->unique();
            $table->bigInteger('id')->comment('Twitch ID');
            $table->text('name')->comment('Twitch Name');
            $table->text('username')->comment('Twitch Username');
            $table->text('email')->comment('Twitch Email');
            $table->text('avatar')->comment('Twitch Avatar URL');
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
        Schema::dropIfExists('twitch_accounts');
    }
};
