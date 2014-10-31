<?php namespace AppStorage;
 
use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\AliasLoader;

class PromptStorageServiceProvider extends ServiceProvider {
 
	public function register()
	{
		$this->app->bind(
		'AppStorage\Prompt\PromptRepository',
		'AppStorage\Prompt\MongoPromptRepository'
		);
		
		$this->app->booting(function()
        {
            $loader = AliasLoader::getInstance();
            $loader->alias('PromptRepository',
            		'AppStorage\Prompt\PromptRepository');
        });
	}
}