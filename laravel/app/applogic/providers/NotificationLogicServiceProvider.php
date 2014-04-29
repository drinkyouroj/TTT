<?php namespace AppLogic\NotificationLogic;

use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\AliasLoader;

class NotificationLogicServiceProvider extends ServiceProvider {

    public function register()
    {  
		$this->app->bind('NotificationLogic', function()
        {
            return new NotificationLogic;
        });
		
		$this->app->booting(function()
        {
            $loader = AliasLoader::getInstance();
            $loader->alias('NotificationLogic', 'AppLogic\NotificationLogic\Facades\NotificationLogicFacade');
        });
    }

}