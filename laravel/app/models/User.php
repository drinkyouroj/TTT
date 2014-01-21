<?php
/*
	Confide Model for user auth and pass.
*/
 
use Zizaco\Confide\ConfideUser;
use Zizaco\Entrust\HasRole;
 	
class User extends ConfideUser {
	
	use HasRole;
	
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
	
	//users who follow this user.
	public function followers()
	{
		return $this->hasMany('Follow', 'user_id');
	}
	
	public function following()
	{
		return $this->hasMany('Follow', 'follower_id');
	}
	
}