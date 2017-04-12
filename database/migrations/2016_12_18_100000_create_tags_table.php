<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tags', function (Blueprint $table) {
            $table->uuid('id');
			$table->string('name')->unique();
			$table->longText('description')->nullable();
			$table->string('url')->nullable();
            $table->uuid('created_by')->nullable();
			$table->uuid('updated_by')->nullable();
			$table->timestamps();
			$table->softDeletes();
			$table->primary('id');
			$table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
			$table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tags');
    }
}
