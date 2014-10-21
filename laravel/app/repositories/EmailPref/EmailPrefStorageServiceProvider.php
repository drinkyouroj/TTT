<?php namespace AppStorage;
 
use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\AliasLoader;

class EmailPrefStorageServiceProvider extends ServiceProvider {
 
	public function register()
	{
		$this->app->bind(
		'AppStorage\EmailPref\EmailPrefRepository',
		'AppStorage\EmailPref\MongoEmailPrefRepository'
		);
		
		$this->app->booting(function()
        {
            $loader = AliasLoader::getInstance();
            $loader->alias('EmailPrefRepository',
            		'AppStorage\EmailPref\EmailPrefRepository');
        });
	}
}