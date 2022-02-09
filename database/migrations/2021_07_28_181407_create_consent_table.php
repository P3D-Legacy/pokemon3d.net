<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConsentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
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
     *
     * @return void
     */
    public function down()
    {
        $tableName = config('consent.table');

        Schema::drop($tableName);
    }
}
