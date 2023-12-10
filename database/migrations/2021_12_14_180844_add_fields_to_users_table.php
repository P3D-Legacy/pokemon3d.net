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
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table
                ->text('about')
                ->nullable()
                ->after('username');
            $table
                ->tinyInteger('gender')
                ->default(0)
                ->after('about');
            $table
                ->string('location')
                ->nullable()
                ->after('gender');
            $table
                ->timestamp('birthdate')
                ->nullable()
                ->after('location');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('about');
            $table->dropColumn('gender');
            $table->dropColumn('location');
            $table->dropColumn('birthdate');
        });
    }
};
