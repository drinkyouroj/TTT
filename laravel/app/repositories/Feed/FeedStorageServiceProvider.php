<?php namespace AppStorage;
 
use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\AliasLoader;

class FeedStorageServiceProvider extends ServiceProvider {
 
	public function register()
	{
		$this->app->bind(
		'AppStorage\Feed\FeedRepository',
		'AppStorage\Feed\MongoFeedRepository'
		);
		
		$this->app->booting(function()
        {
            $loader = AliasLoader::getInstance();
            $loader->alias('FeedRepository',
            		'AppStorage\Feed\FeedRepository');
        });
	}
}