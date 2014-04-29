<?php namespace AppLogic\CommentLogic\Facades;

class CommentLogicFacade extends \Illuminate\Support\Facades\Facade {
	
    protected static function getFacadeAccessor() {
    	return 'CommentLogic'; 
	}
	
}