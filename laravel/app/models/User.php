<?php
/*
	Confide Model for user auth and pass.
*/
 
use Zizaco\Confide\ConfideUser;
 	
class User extends ConfideUser {
	
	
}
/*
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableInterface;
class User extends Eloquent implements UserInterface, RemindableInterface {
	protected $table = 'users';
	protected $hidden = array('password');
	public function getAuthIdentifier()
	{
		return $this->getKey();
	}

	public function getAuthPassword()
	{
		return $this->password;
	}
	public function getReminderEmail()
	{
		return $this->email;
	}

}
 
 */