<?php

declare(strict_types=1);

/**
 * Laravel Categorizable Package by Ali Bayat.
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Kalnoy\Nestedset\NestedSet;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('slug');
            $table->string('type')->default('default');
            NestedSet::columns($table);
            $table->timestamps();
        });

        Schema::create('categories_models', function (Blueprint $table) {
            $table->integer('category_id');
            $table->morphs('model');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categories');
        Schema::dropIfExists('categories_models');
    }
};
