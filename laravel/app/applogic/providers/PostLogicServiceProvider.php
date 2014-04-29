<?php namespace AppLogic\PostLogic;

use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\AliasLoader;

class PostLogicServiceProvider extends ServiceProvider {

    public function register()
    {  
		$this->app->bind('PostLogic', function()
        {
            return new PostLogic;
        });
		
		$this->app->booting(function()
        {
            $loader = AliasLoader::getInstance();
            $loader->alias('PostLogic', 'AppLogic\PostLogic\Facades\PostLogicFacade');
        });
    }

}