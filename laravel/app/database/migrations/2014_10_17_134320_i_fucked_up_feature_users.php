<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class IFuckedUpFeatureUsers extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('featured_users',function(Blueprint $table) 
		{
			$table->text('excerpt');
			$table->dropColumn('exerpt');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('featured_users', function(Blueprint $table) {
			$table->dropColumn('excerpt');
		});
	}

}
