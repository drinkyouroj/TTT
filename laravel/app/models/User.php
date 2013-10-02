<?php
/*
	Confide Model for user auth and pass.
*/
 
use Zizaco\Confide\ConfideUser;
use Zizaco\Entrust\HasRole;
 	
class User extends ConfideUser {
	
	public function posts()
    {
        return $this->hasMany('Post', 'user_id');
    }
	
	public function comments()
	{
		return $this->hasMany('Comment', 'user_id');
	}
	
	public function inbox()
	{
		return $this->hasMany('Message', 'to_uid');
	}
	
	public function sent()
	{
		return $this->hasMany('Message', 'from_uid');
	}
	
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