<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActivityTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('activities', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('user_id');//Who owns this?
			$table->integer('action_id');//Who did this?
			$table->integer('post_id');//The post that's being placed in activity
			$table->enum('post_type',array('post', 'repost'));//What kind of an activity is this (differentiate with user_id if its your post or following's new post)
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
		Schema:drop('activities');
	}

}