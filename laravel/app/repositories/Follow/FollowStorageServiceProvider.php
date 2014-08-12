<?php namespace AppStorage;
 
use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\AliasLoader;

class FollowStorageServiceProvider extends ServiceProvider {
 
	public function register()
	{
		$this->app->bind(
		'AppStorage\Follow\FollowRepository',
		'AppStorage\Follow\EloquentFollowRepository'
		);
		
		$this->app->booting(function()
        {
            $loader = AliasLoader::getInstance();
            $loader->alias('FollowRepository',
            		'AppStorage\Follow\FollowRepository');
        });
	}
}