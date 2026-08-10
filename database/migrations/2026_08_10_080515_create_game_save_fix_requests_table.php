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
        Schema::create('game_save_fix_requests', function (Blueprint $table) {
            $table->uuid('uuid')->primary();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('assignee_id')->nullable()->constrained('users')->nullOnDelete();
            $table->text('description');
            $table->string('status');
            $table->timestamp('consent_accepted_at');
            $table->text('consent_text');
            $table->timestamp('resolved_at')->nullable();
            $table->boolean('notify_database')->default(true);
            $table->boolean('notify_mail')->default(true);
            $table->timestamp('stale_notified_at')->nullable();
            $table->timestamps();

            $table->index(['status', 'updated_at']);
            $table->index(['user_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('game_save_fix_requests');
    }
};
