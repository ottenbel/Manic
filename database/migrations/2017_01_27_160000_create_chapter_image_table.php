<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChapterImageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chapter_image', function (Blueprint $table) {
			$table->uuid('chapter_id');
			$table->uuid('image_id');
			$table->integer('page_number');
			$table->timestamps();
			$table->foreign('chapter_id')->references('id')->on('chapters')->onDelete('cascade');
			$table->foreign('image_id')->references('id')->on('images')->onDelete('cascade');
			$table->unique(['chapter_id', 'page_number']);
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('chapter_image');
    }
}
