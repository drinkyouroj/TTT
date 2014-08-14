<?php namespace AppStorage;
 
use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\AliasLoader;


class ActivityStorageServiceProvider extends ServiceProvider {
 
	public function register()
	{
		$this->app->bind(
		'AppStorage\Activity\ActivityRepository',
		'AppStorage\Activity\EloquentActivityRepository'
		);
		
		$this->app->booting(function()
        {
            $loader = AliasLoader::getInstance();
            $loader->alias('ActivityRepository',
            		'AppStorage\Activity\ActivityRepository');
        });
	}
}