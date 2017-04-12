<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTagAliasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		Schema::create('tag_alias', function (Blueprint $table) {
			$table->uuid('id');
			$table->uuid('user_id')->nullable();
			$table->uuid('tag_id');
			$table->string('alias');
			$table->uuid('created_by')->nullable();
			$table->uuid('updated_by')->nullable();
			$table->timestamps();
			$table->softDeletes();
			$table->primary('id');
			$table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
			$table->foreign('tag_id')->references('id')->on('tags')->onDelete('cascade');
			$table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
			$table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
			$table->unique(['user_id', 'alias']);
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tag_alias');
    }
}
