<?php namespace AppStorage;
 
use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\AliasLoader;

class CommentStorageServiceProvider extends ServiceProvider {
 
	public function register()
	{
		$this->app->bind(
		'AppStorage\Comment\CommentRepository',
		'AppStorage\Comment\MongoCommentRepository'
		);
		
		$this->app->booting(function()
        {
            $loader = AliasLoader::getInstance();
            $loader->alias('CommentRepository',
            		'AppStorage\Comment\CommentRepository');
        });
	}
}