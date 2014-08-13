<?php namespace AppStorage;
 
use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\AliasLoader;

class FavoriteStorageServiceProvider extends ServiceProvider {
 
	public function register()
	{
		$this->app->bind(
		'AppStorage\Favorite\FavoriteRepository',
		'AppStorage\Favorite\EloquentFavoriteRepository'
		);
		
		$this->app->booting(function()
        {
            $loader = AliasLoader::getInstance();
            $loader->alias('FavoriteRepository',
            		'AppStorage\Favorite\FavoriteRepository');
        });
	}
}