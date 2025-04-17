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
        $tableName = config('consent.table');

        Schema::create($tableName, function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->index();
            $table->morphs('model');
            $table->boolean('given')->default(0);
            $table->text('text')->nullable();
            $table->mediumText('meta')->nullable();
            $table->timestamps();
            $table->index(['model_id', 'model_type', 'name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tableName = config('consent.table');

        Schema::drop($tableName);
    }
};
