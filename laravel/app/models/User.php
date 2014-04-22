<?php
/*
	Confide Model for user auth and pass.
*/
 
use Zizaco\Confide\ConfideUser;
use Zizaco\Entrust\HasRole;
use Illuminate\Auth\UserInterface;

class User extends ConfideUser implements UserInterface {
	
	use HasRole;
	
	protected $softDelete = true;
	
//	protected $guarded = array('password','email','confirmation_code');
	/**
     * Validation rules
     */
    public static $rules = array(
        'username' => 'required|alpha_dash|unique:users',
        'email' => 'required|email|unique:users',
        'password' => 'required|between:4,11|confirmed',
        'password_confirmation' => 'between:4,11',
    );
	
	public function posts()
    {
        return $this->hasMany('Post', 'user_id');
    }
	
	public function comments()
	{
		return $this->hasMany('Comment', 'user_id');
	}
	
	public function featured()
	{
		return $this->belongsTo('Post', 'featured');
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