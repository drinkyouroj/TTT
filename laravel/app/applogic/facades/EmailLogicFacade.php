<?php namespace AppLogic\EmailLogic\Facades;

class EmailLogicFacade extends \Illuminate\Support\Facades\Facade {
	
    protected static function getFacadeAccessor() {
    	return 'EmailLogic'; 
	}
	
}