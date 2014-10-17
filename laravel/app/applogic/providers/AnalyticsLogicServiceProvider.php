<?php namespace AppLogic\AnalyticsLogic;

use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\AliasLoader;

class AnalyticsLogicServiceProvider extends ServiceProvider {

    public function register()
    {  
		$this->app->bind('AnalyticsLogic', function()
        {
            return new AnalyticsLogic;
        });
		
		$this->app->booting(function()
        {
            $loader = AliasLoader::getInstance();
            $loader->alias('AnalyticsLogic', 'AppLogic\AnalyticsLogic\Facades\AnalyticsLogicFacade');
        });
    }

}