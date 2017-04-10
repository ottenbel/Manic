<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCharacterCollectionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		Schema::create('character_collection', function (Blueprint $table) {
			$table->uuid('character_id');
			$table->uuid('collection_id');
			$table->boolean('primary');
			$table->timestamps();
			$table->foreign('character_id')->references('id')->on('characters')->onDelete('cascade');
			$table->foreign('collection_id')->references('id')->on('collections')->onDelete('cascade');
			$table->unique(['character_id', 'collection_id']);
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('character_collection');
    }
}
