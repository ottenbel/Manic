<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCollectionSeriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('collection_series', function (Blueprint $table) {
			$table->uuid('collection_id');
			$table->uuid('series_id');
			$table->boolean('primary');
			$table->foreign('collection_id')->references('id')->on('collections')->onDelete('cascade');
			$table->foreign('series_id')->references('id')->on('series')->onDelete('cascade');
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('collection_series');
    }
}
