<?php namespace AppLogic\PostLogic\Facades;

class PostLogicFacade extends \Illuminate\Support\Facades\Facade {
	
    protected static function getFacadeAccessor() {
    	return 'PostLogic'; 
	}
	
}