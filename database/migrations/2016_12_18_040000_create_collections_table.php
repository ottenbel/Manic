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
			$table->string('name')->unique();
			$table->longText('description')->nullable();
			$table->boolean('canonical')->nullable();
			$table->uuid('parent_id')->nullable();
			$table->uuid('language_id')->nullable();
			$table->uuid('rating_id')->nullable();
			$table->uuid('status_id')->nullable();
			$table->uuid('created_by');
			$table->uuid('updated_by');
			$table->timestamps();
			$table->softDeletes();
			$table->primary('id');
			$table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
			$table->foreign('updated_by')->references('id')->on('users')->onDelete('cascade');
			$table->foreign('cover')->references('id')->on('images')->onDelete('set null');
			$table->foreign('language_id')->references('id')->on('languages')->onDelete('set null');
			$table->foreign('rating_id')->references('id')->on('ratings')->onDelete('set null');
			$table->foreign('status_id')->references('id')->on('statuses')->onDelete('set null');
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
