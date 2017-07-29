<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChapterExportTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		Schema::create('chapter_exports', function (Blueprint $table) {
			$table->uuid('id');
			$table->uuid('chapter_id');
			$table->string('path');
			$table->date('last_downloaded');
			$table->primary('id');
			$table->foreign('chapter_id')->references('id')->on('chapters')->onDelete('cascade');
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('chapter_exports');
    }
}
