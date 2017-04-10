<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChapterScanalatorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chapter_scanalator', function (Blueprint $table) {
			$table->uuid('chapter_id');
			$table->uuid('scanalator_id');
			$table->boolean('primary');
			$table->timestamps();
			$table->foreign('chapter_id')->references('id')->on('chapters')->onDelete('cascade');
			$table->foreign('scanalator_id')->references('id')->on('scanalators')->onDelete('cascade');
			$table->unique(['chapter_id', 'scanalator_id']);
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('chapter_scanalator');
    }
}
