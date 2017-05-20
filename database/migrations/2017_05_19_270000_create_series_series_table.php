<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSeriesSeriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		Schema::create('series_series', function (Blueprint $table) {
			$table->uuid('parent_id');
			$table->uuid('child_id');
			$table->foreign('parent_id')->references('id')->on('series')->onDelete('cascade');
			$table->foreign('child_id')->references('id')->on('series')->onDelete('cascade');
			$table->unique(['parent_id', 'child_id']);
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('series_series');
    }
}
