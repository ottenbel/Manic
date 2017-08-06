<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCollectionExportTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		Schema::create('collection_exports', function (Blueprint $table) {
			$table->uuid('id');
			$table->uuid('collection_id');
			$table->string('path');
			$table->date('last_downloaded');
			$table->primary('id');
			$table->foreign('collection_id')->references('id')->on('collections')->onDelete('cascade');
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('collection_exports');
    }
}
