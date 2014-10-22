<?php namespace AppLogic\EmailLogic;

use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\AliasLoader;

class EmailLogicServiceProvider extends ServiceProvider {

    public function register()
    {  
		$this->app->bind('EmailLogic', function()
        {
            return new EmailLogic;
        });
		
		$this->app->booting(function()
        {
            $loader = AliasLoader::getInstance();
            $loader->alias('EmailLogic', 'AppLogic\EmailLogic\Facades\EmailLogicFacade');
        });
    }

}