<?php namespace AppStorage;
 
use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\AliasLoader;

//TODO This whole Storage abstraction is a todo on its own.  we're not done with this yet and therefore its not implemented anywhere.
class PostFlaggedStorageServiceProvider extends ServiceProvider {
 
	public function register()
	{
		$this->app->bind(
		'AppStorage\PostFlagged\PostFlaggedRepository',
		'AppStorage\PostFlagged\EloquentPostFlaggedRepository'
		);
		
		$this->app->booting(function()
        {
            $loader = AliasLoader::getInstance();
            $loader->alias('PostFlaggedRepository',
            		'AppStorage\PostFlagged\PostFlaggedRepository');
        });
	}
}