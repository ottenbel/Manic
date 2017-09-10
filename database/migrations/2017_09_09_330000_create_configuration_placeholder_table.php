<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConfigurationPlaceholderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		Schema::create('configuration_placeholder', function (Blueprint $table) {
			$table->uuid('id')->nullable();
			$table->uuid('user_id')->nullable();
			$table->string('key');
			$table->string('value');
			$table->string('description', 250);
			$table->integer('priority');
			$table->uuid('created_by')->nullable();
			$table->uuid('updated_by')->nullable();
			$table->timestamps();
			$table->softDeletes();
			$table->primary('id');
			$table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
			$table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
			$table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
			$table->unique(['user_id', 'key']);
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('configuration_placeholder');
    }
}
