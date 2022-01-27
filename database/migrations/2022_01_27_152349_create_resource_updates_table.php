<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResourceUpdatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('resource_updates', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('body');
            $table->unsignedBigInteger('resource_id');
            $table->unsignedBigInteger('game_version_id');
            $table->softDeletes();
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
        Schema::dropIfExists('resource_updates');
    }
}
