<?php namespace AppStorage;
 
use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\AliasLoader;

//TODO This whole Storage abstraction is a todo on its own.  we're not done with this yet and therefore its not implemented anywhere.
class PostStorageServiceProvider extends ServiceProvider {
 
	public function register()
	{
		$this->app->bind(
		'AppStorage\Post\PostRepository',
		'AppStorage\Post\EloquentPostRepository'
		);
		
		$this->app->booting(function()
        {
            $loader = AliasLoader::getInstance();
            $loader->alias('PostRepository',
            		'AppStorage\Post\PostRepository');
        });
	}
}