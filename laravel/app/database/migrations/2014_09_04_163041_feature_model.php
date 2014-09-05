<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FeatureModel extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('featured', function(Blueprint $table)
		{
			$table->string('position');//marks the position of the featured page
			$table->boolean('front');//Marks whether the featured is on the front at this time.
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
			$table->dropColumn('position');
			$table->dropColumn('front');
		});
	}

}
