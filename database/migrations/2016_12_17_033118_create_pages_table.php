<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pages', function(Blueprint $table){
			$table->uuid('id');
			$table->uuid('chapter_id');
			$table->unsignedInteger('page_number');
			$table->uuid('image_id');
			$table->uuid('created_by');
			$table->uuid('updated_by');
			$table->timestamps();
			$table->softDeletes();
			$table->primary('id');
			$table->foreign('created_by')->references('id')->on('users');
			$table->foreign('updated_by')->references('id')->on('users');
			$table->foreign('chapter_id')->references('id')->on('chapters');
			$table->foreign('image_id')->references('id')->on('images');
			$table->unique(['associated_chapter', 'page_number']);
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pages');
    }
}
