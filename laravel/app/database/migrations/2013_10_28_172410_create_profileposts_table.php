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
			$table->integer('post_id');
			$table->integer('user_id');
			$table->enum('post_type', array(
						'post',
						'repost',
						'favorite'
						));
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
