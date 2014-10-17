<?php namespace AppStorage;
 
use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\AliasLoader;

class FeaturedUserStorageServiceProvider extends ServiceProvider {
 
	public function register()
	{
		$this->app->bind(
		'AppStorage\FeaturedUser\FeaturedUserRepository',
		'AppStorage\FeaturedUser\EloquentFeaturedUserRepository'
		);
		
		$this->app->booting(function()
        {
            $loader = AliasLoader::getInstance();
            $loader->alias('FeaturedUserRepository',
            		'AppStorage\FeaturedUser\FeaturedUserRepository');
        });
	}
}