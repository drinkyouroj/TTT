<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProfilepostsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('profile_posts', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('profile_id');//This is the id that should be used to search for (EX to compare against Session::get('user_id'))  As in this is the profile owner's ID 
			$table->integer('post_id');//The post associated witht he feed item
			$table->integer('user_id');//user associated with the feed item
			$table->enum('post_type', array(
						'post',
						'repost',
						'favorite'
						));				//Action associated with the feed item.
			$table->softDeletes();
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
		Schema::drop('profile_posts');
	}

}
