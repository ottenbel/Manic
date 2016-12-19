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
			$table->uuid('id');
			$table->uuid('collection_id');
			$table->uuid('series_id');
			$table->boolean('primary');
			$table->uuid('created_by');
			$table->uuid('updated_by');
			$table->timestamps();
			$table->softDeletes();
			$table->primary('id');
			$table->foreign('collection_id')->references('id')->on('collections');
			$table->foreign('series_id')->references('id')->on('series');
			$table->foreign('created_by')->references('id')->on('users');
			$table->foreign('updated_by')->references('id')->on('users');
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
