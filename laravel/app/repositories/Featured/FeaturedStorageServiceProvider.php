<?php namespace AppStorage;
 
use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\AliasLoader;

class FeaturedStorageServiceProvider extends ServiceProvider {
 
	public function register()
	{
		$this->app->bind(
		'AppStorage\Featured\FeaturedRepository',
		'AppStorage\Featured\EloquentFeaturedRepository'
		);
		
		$this->app->booting(function()
        {
            $loader = AliasLoader::getInstance();
            $loader->alias('FeaturedRepository',
            		'AppStorage\Featured\FeaturedRepository');
        });
	}
}