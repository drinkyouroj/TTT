<?php namespace AppLogic\CategoryLogic;

use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\AliasLoader;

class CategoryLogicServiceProvider extends ServiceProvider {

    public function register()
    {  
		$this->app->bind('CategoryLogic', function()
        {
            return new CategoryLogic;
        });
		
		$this->app->booting(function()
        {
            $loader = AliasLoader::getInstance();
            $loader->alias('CategoryLogic', 'AppLogic\CategoryLogic\Facades\CategoryLogicFacade');
        });
    }

}