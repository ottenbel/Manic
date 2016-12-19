<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArtistCollectionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		Schema::create('artist_collection', function (Blueprint $table) {
			$table->uuid('id');
			$table->uuid('artist_id');
			$table->uuid('collection_id');
			$table->boolean('primary');
			$table->uuid('created_by');
			$table->uuid('updated_by');
			$table->timestamps();
			$table->softDeletes();
			$table->primary('id');
			$table->foreign('artist_id')->references('id')->on('artists');
			$table->foreign('collection_id')->references('id')->on('collections');
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
        Schema::dropIfExists('artist_collection');
    }
}
