<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVolumeExportTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		Schema::create('volume_exports', function (Blueprint $table) {
			$table->uuid('id');
			$table->uuid('volume_id');
			$table->string('path');
			$table->date('last_downloaded');
			$table->primary('id');
			$table->foreign('volume_id')->references('id')->on('volumes')->onDelete('cascade');
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('volume_exports');
    }
}
