<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMessagesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('messages', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('from_uid');
			$table->integer('to_uid');
			$table->integer('reply_id');
			$table->text('body');
			$table->dateTime('last_mod');//This stores the last date that the message was replied to.
			$table->integer('last_id');//Stores the latest message id.
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
		Schema::drop('messages');
	}

}
