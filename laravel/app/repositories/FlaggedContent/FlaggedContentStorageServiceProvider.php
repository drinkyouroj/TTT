<?php namespace AppStorage;
 
use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\AliasLoader;

class FlaggedContentStorageServiceProvider extends ServiceProvider {
 
	public function register()
	{
		$this->app->bind(
		'AppStorage\FlaggedContent\FlaggedContentRepository',
		'AppStorage\FlaggedContent\MongoFlaggedContentRepository'
		);
		
		$this->app->booting(function()
        {
            $loader = AliasLoader::getInstance();
            $loader->alias('FlaggedContentRepository',
            		'AppStorage\FlaggedContent\FlaggedContentRepository');
        });
	}
}