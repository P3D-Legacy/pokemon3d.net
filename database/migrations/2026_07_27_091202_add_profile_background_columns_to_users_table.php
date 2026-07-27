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
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'gamejolt_emblem')) {
                $table->string('gamejolt_emblem')->nullable()->after('timezone');
            }

            if (! Schema::hasColumn('users', 'profile_background')) {
                $table->string('profile_background')->nullable()->after('gamejolt_emblem');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['gamejolt_emblem', 'profile_background']);
        });
    }
};
