<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVolumesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('volumes', function(Blueprint $table){
			$table->uuid('id');
			$table->uuid('collection_id');
			$table->uuid('cover')->nullable();
			$table->unsignedInteger('volume_number');
			$table->string('name')->nullable();
			$table->uuid('created_by');
			$table->uuid('updated_by');
			$table->timestamps();
			$table->softDeletes();
			$table->primary('id');
			$table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
			$table->foreign('updated_by')->references('id')->on('users')->onDelete('cascade');
			$table->foreign('collection_id')->references('id')->on('collections')->onDelete('cascade');
			$table->foreign('cover')->references('id')->on('images')->onDelete('set null');
			$table->unique(['collection_id', 'volume_number']);
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('volumes');
    }
}
