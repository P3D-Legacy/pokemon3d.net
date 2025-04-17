<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $duplicateRecords = DB::table('discord_accounts')
            ->selectRaw('id, count(`id`) as `occurences`')
            ->groupBy('id')
            ->having('occurences', '>', 1)
            ->get();
        foreach ($duplicateRecords as $duplicateRecord) {
            DB::table('discord_accounts')
                ->where('id', $duplicateRecord->id)
                ->whereNotNull('deleted_at')
                ->delete();
        }
        Schema::table('discord_accounts', function (Blueprint $table) {
            $table->dropColumn('aid');
            $table->primary('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
