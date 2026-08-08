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
        Schema::table('resource_updates', function (Blueprint $table) {
            $table->string('external_download_url', 2048)->nullable()->after('downloads');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('resource_updates', function (Blueprint $table) {
            $table->dropColumn('external_download_url');
        });
    }
};
