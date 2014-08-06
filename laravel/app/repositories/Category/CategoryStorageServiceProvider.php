<?php namespace AppStorage;
 
use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\AliasLoader;


class CategoryStorageServiceProvider extends ServiceProvider {
 
	public function register()
	{
		$this->app->bind(
		'AppStorage\Category\CategoryRepository',
		'AppStorage\Category\EloquentCategoryRepository'
		);
		
		$this->app->booting(function()
        {
            $loader = AliasLoader::getInstance();
            $loader->alias('CategoryRepository',
            		'AppStorage\Category\CategoryRepository');
        });
	}
}