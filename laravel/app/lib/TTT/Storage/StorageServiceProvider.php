<?php namespace TTT\Storage;
 
use Illuminate\Support\ServiceProvider;

//TODO This whole Storage abstraction is a todo on its own.  we're not done with this yet and therefore its not implemented anywhere.
class StorageServiceProvider extends ServiceProvider {
 
	public function register()
	{
		$this->app->bind(
		'TTT\Storage\User\UserRepository',
		'TTT\Storage\User\ConfideUserRepository'
		);
	}
 
}