<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGamejoltAccountTrophiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("gamejolt_account_trophies", function (
            Blueprint $table
        ) {
            $table->increments("aid");
            $table->uuid("uuid")->unique();
            $table->unsignedBigInteger("id")->comment("Gamejolt Trophy ID");
            $table->string("title")->comment("Gamejolt Trophy Title");
            $table->string("difficulty")->comment("Gamejolt Trophy Difficulty");
            $table
                ->string("description")
                ->comment("GameJolt Trophy Description");
            $table->text("image_url")->comment("GameJolt Trophy Image URL");
            $table
                ->boolean("achieved")
                ->default(false)
                ->comment("GameJolt Trophy Achieved By User");
            $table
                ->unsignedBigInteger("gamejolt_account_id")
                ->comment("GameJolt Account ID");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists("gamejolt_account_trophies");
    }
}
