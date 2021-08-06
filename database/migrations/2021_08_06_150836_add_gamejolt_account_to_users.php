<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddGamejoltAccountToUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->text('gamejolt_username')->after('profile_photo_path')->nullable();
            $table->string('gamejolt_token')->after('gamejolt_username')->nullable();
            $table->timestamp('gamejolt_updated_at')->after('gamejolt_token')->nullable();
            $table->timestamp('gamejolt_verified_at')->after('gamejolt_updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('gamejolt_username', 'gamejolt_token', 'gamejolt_updated_at', 'gamejolt_verified_at');
        });
    }
}
