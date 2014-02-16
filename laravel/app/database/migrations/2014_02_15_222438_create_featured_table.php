<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFeaturedTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('featured', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('user_id');//Who owns this?
			$table->integer('post_id');//The post that's being placed in activity
			$table->integer('height');
			$table->integer('width');
			$table->integer('order');
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		//
		Schema::drop('featured');
	}

}