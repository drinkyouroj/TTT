<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SavesAddReadAgainIMessedUpAgain extends Migration {

	
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('favorites', function(Blueprint $table)
		{
			$table->boolean('read');			
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('favorites', function(Blueprint $table)
		{
			$table->dropColumn('read');			
		});
	}

}
