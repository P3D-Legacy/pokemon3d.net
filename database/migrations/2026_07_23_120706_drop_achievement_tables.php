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
        Schema::dropIfExists('achievement_progress');
        Schema::dropIfExists('achievement_details');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('achievement_details', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('description');
            $table->unsignedInteger('points')->default(1);
            $table->boolean('secret')->default(false);
            $table->string('class_name');
            $table->timestamps();
        });

        Schema::create('achievement_progress', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->unsignedBigInteger('achievement_id');
            $table->morphs('achiever');
            $table->unsignedInteger('points')->default(0);
            $table->timestamp('unlocked_at')->nullable()->default(null);
            $table->timestamps();

            $table->foreign('achievement_id')->references('id')->on('achievement_details');
        });
    }
};
