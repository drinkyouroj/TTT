<?php namespace AppLogic\NotificationLogic\Facades;

class NotificationLogicFacade extends \Illuminate\Support\Facades\Facade {
	
    protected static function getFacadeAccessor() {
    	return 'NotificationLogic'; 
	}
	
}