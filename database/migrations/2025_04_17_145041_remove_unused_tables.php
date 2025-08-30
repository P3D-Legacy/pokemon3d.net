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
        Schema::dropIfExists('nova_field_attachments');
        Schema::dropIfExists('nova_notifications');
        Schema::dropIfExists('nova_pending_field_attachments');
        Schema::dropIfExists('twitter_accounts');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
