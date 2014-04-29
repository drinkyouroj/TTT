<?php namespace Helper\SolariumHelper;

use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\AliasLoader;

class SolariumHelperServiceProvider extends ServiceProvider {

    public function register()
    {  
		$this->app->bind('SolariumHelper', function()
        {
            return new SolariumHelper;
        });
		
		$this->app->booting(function()
        {
            $loader = AliasLoader::getInstance();
            $loader->alias('SolariumHelper', 'Helper\SolariumHelper\Facades\SolariumHelperFacade');
        });
    }

}