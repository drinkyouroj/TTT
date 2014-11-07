<?php namespace AppStorage;
 
use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\AliasLoader;

class WeeklyDigestStorageServiceProvider extends ServiceProvider {
 
	public function register()
	{
		$this->app->bind(
		'AppStorage\WeeklyDigest\WeeklyDigestRepository',
		'AppStorage\WeeklyDigest\MongoWeeklyDigestRepository'
		);
		
		$this->app->booting(function()
        {
            $loader = AliasLoader::getInstance();
            $loader->alias('WeeklyDigestRepository',
            		'AppStorage\WeeklyDigest\WeeklyDigestRepository');
        });
	}
}