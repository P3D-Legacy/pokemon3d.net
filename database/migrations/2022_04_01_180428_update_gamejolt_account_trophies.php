<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('gamejolt_account_trophies', function (Blueprint $table) {
            $table->bigIncrements('aid')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('gamejolt_account_trophies', function (Blueprint $table) {
            $table->increments('aid')->change();
        });
    }
};
