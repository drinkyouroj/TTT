<?php namespace AppLogic\FollowLogic;

use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\AliasLoader;

class FollowLogicServiceProvider extends ServiceProvider {

    public function register()
    {  
		$this->app->bind('FollowLogic', function()
        {
            return new FollowLogic;
        });
		
		$this->app->booting(function()
        {
            $loader = AliasLoader::getInstance();
            $loader->alias('FollowLogic', 'AppLogic\FollowLogic\Facades\FollowLogicFacade');
        });
    }

}