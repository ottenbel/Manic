<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCollectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('collections', function(Blueprint $table){
			$table->uuid('id');
			$table->uuid('cover')->nullable();
			$table->string('name');
			$table->longText('description');
			$table->boolean('canonical');
			$table->uuid('parent_id')->nullable();
			$table->uuid('language_id');
			$table->uuid('created_by');
			$table->uuid('updated_by');
			$table->timestamps();
			$table->softDeletes();
			$table->primary('id');
			$table->foreign('created_by')->references('id')->on('users');
			$table->foreign('updated_by')->references('id')->on('users');
			$table->foreign('cover')->references('id')->on('images');
			$table->foreign('language_id')->references('id')->on('languages');
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('collections');
    }
}
