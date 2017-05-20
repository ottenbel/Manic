<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArtistArtistTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		Schema::create('artist_artist', function (Blueprint $table) {
			$table->uuid('parent_id');
			$table->uuid('child_id');
			$table->foreign('parent_id')->references('id')->on('artists')->onDelete('cascade');
			$table->foreign('child_id')->references('id')->on('artists')->onDelete('cascade');
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
        Schema::dropIfExists('artist_artist');
    }
}
