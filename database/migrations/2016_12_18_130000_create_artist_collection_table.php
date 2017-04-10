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
			$table->uuid('artist_id');
			$table->uuid('collection_id');
			$table->boolean('primary');
			$table->timestamps();
			$table->foreign('artist_id')->references('id')->on('artists')->onDelete('cascade');
			$table->foreign('collection_id')->references('id')->on('collections')->onDelete('cascade');
			$table->unique(['artist_id', 'collection_id']);
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
