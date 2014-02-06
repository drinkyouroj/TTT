<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotificationsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('notifications', function(Blueprint $table)
		{
			$table->increments('id');
			$table->boolean('noticed');
			$table->enum('notification_type',array(
						'repost',
						'favorite',
						'comment',
						'follow',
						'message'
					));
			$table->integer('post_id');//What was affected?
			$table->integer('action_id');//Who did it?
			$table->integer('user_id');//Whose notification is this going to?
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
		Schema::drop('notifications');
	}

}
