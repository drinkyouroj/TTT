<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('posts', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('user_id');
			$table->string('title');
			$table->string('alias')->unique();//This has to be unique.
			$table->string('tagline_1');
			$table->string('tagline_2');
			$table->string('tagline_3');
			$table->string('category');
			$table->enum('story_type', array(
					'story',
					'advice',
					'thought'
					));
			$table->string('image');
			$table->text('body');
			$table->boolean('featured');
			$table->date('featured_date');
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
		Schema::drop('posts');
	}

}
