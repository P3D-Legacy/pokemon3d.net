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
        Schema::create('servers', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('uuid');
            $table->string('name');
            $table->string('host');
            $table->integer('port');
            $table->text('description')->nullable();
            $table->boolean('official')->default(false);
            $table->integer('ping')->nullable();
            $table->timestamp('last_check_at')->nullable();
            $table->timestamp('last_online_at')->nullable();
            $table->boolean('active')->default(true);
            $table->unsignedBigInteger('user_id');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('servers');
    }
};
