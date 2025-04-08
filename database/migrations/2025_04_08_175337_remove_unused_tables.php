<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Drop the unused tables
        Schema::dropIfExists('health_check_result_history_items');
        Schema::dropIfExists('monitored_scheduled_task_log_items');
        Schema::dropIfExists('monitored_scheduled_tasks');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
