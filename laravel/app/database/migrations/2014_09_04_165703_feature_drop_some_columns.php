<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FeatureDropSomeColumns extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('featured', function(Blueprint $table)
		{
			$table->dropColumn('height');
			$table->dropColumn('width');
			$table->dropColumn('order');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('featured', function(Blueprint $table)
		{
			$table->integer('height');
			$table->integer('width');
			$table->integer('order');
		});
	}

}
