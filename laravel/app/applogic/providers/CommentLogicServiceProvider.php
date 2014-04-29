<?php namespace AppLogic\CommentLogic;

use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\AliasLoader;

class CommentLogicServiceProvider extends ServiceProvider {

	public function register() {
		$this -> app -> bind('CommentLogic', function() {
			return new CommentLogic;
		});

		$this -> app -> booting(function() {
			$loader = AliasLoader::getInstance();
			$loader -> alias('CommentLogic', 'AppLogic\CommentLogic\Facades\CommentLogicFacade');
		});
	}

}
