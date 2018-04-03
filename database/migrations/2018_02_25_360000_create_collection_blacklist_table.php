<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCollectionBlacklistTable extends Migration
{
	/**
     * Run the migrations.
     *
     * @return void
     */
	public function up()
    {
		Schema::create('collection_blacklist', function (Blueprint $table) {	
			$table->uuid('id')->nullable();
			$table->uuid('user_id')->nullable();
			$table->uuid('collection_id')->nullable();
			$table->uuid('created_by')->nullable();
			$table->uuid('updated_by')->nullable();
			$table->timestamps();
			$table->softDeletes();
			$table->primary('id');
			$table->foreign('user_id')->references('id')->on('users');
			$table->foreign('collection_id')->references('id')->on('collections');
			$table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
			$table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
			$table->unique(['user_id', 'collection_id']);
		});
	}
	
	/**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('collection_blacklist');
    }
}