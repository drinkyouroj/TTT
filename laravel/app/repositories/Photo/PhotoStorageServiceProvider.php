<?php namespace AppStorage;
 
use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\AliasLoader;

class PhotoStorageServiceProvider extends ServiceProvider {
 
	public function register()
	{
		$this->app->bind(
		'AppStorage\Photo\PhotoRepository',
		'AppStorage\Photo\FlickrPhotoRepository'
		);
		
		$this->app->booting(function()
        {
            $loader = AliasLoader::getInstance();
            $loader->alias('PhotoRepository',
            		'AppStorage\Photo\PhotoRepository');
        });
	}
}