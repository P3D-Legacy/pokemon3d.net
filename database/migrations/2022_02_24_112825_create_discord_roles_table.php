<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('discord_roles', function (Blueprint $table) {
            $table->id('aid');
            $table->bigInteger('color')->default(0);
            $table->boolean('hoist')->default(false);
            $table->bigInteger('id')->comment('Discord Role ID');
            $table->boolean('managed')->default(false);
            $table->boolean('mentionable')->default(false);
            $table->string('name');
            $table->string('permissions')->default(0);
            $table->string('position')->default(0);
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
        Schema::dropIfExists('discord_roles');
    }
};
