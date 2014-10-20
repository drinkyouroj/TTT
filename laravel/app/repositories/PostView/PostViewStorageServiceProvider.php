<?php namespace AppStorage;
 
use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\AliasLoader;

//TODO This whole Storage abstraction is a todo on its own.  we're not done with this yet and therefore its not implemented anywhere.
class PostViewStorageServiceProvider extends ServiceProvider {
 
	public function register()
	{
		$this->app->bind(
		'AppStorage\PostView\PostViewRepository',
		'AppStorage\PostView\MongoPostViewRepository'
		);
		
		$this->app->booting(function()
        {
            $loader = AliasLoader::getInstance();
            $loader->alias('PostViewRepository',
            		'AppStorage\PostView\MongoPostViewRepository');
        });
	}
}