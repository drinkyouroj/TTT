<?php
use Zizaco\Entrust\HasRole;
use Illuminate\Auth\UserInterface;

use Illuminate\Database\Eloquent\SoftDeletingTrait;

//We've totally taken Confide out of this.
class User extends Eloquent implements UserInterface {
	use SoftDeletingTrait;
	use HasRole;

	protected $table = 'users';
	protected $softDelete = true;
	protected $dates = ['deleted_at'];

    public function __construct() {
    	parent::__construct();
    	
    	Validator::extend('reservation_cap', function($attribute, $value, $parameters) {
		    $cap = $parameters[0];
		    if(strlen($value)) {
		    	$users = User::where( 'email', $value )->count();
		    	return $users < $cap;
		    } else {
		    	return true;
		    }
		});

		Validator::extend('math_captcha', function($attribute, $value, $parameters) {
			$num1 = intval(Session::get('signup_num1'));
			$num2 = intval(Session::get('signup_num2'));
			$sum = $num1 + $num2;
			return intval($value) === $sum;
		});
    }
	
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
		return $this->hasMany('Follow', 'user_id')
					->select('user_id','follower_id');
	}
	
	public function following()
	{
		return $this->hasMany('Follow', 'follower_id');
	}

	public function validate($input)
	{
		return Validator::make( $input, 
			// Validation rules
			array(
		        'username' => 'required|min:3|max:15|alpha_dash|unique:users',
		        'email' => 'email|reservation_cap:3',
		        'password' => 'required|between:4,11|confirmed',
		        'password_confirmation' => 'between:4,11',
		        'captcha' => 'required|math_captcha',
    		), 
			// Error messages
			array(
				'captcha.required' => 'Let us know that you are a human: fill out the captcha!',
				'captcha.math_captcha' => 'Are you a human? You got the captcha wrong!'
    		)

    	);
	}

	public function validateJSON($input)
	{
		return Validator::make($input, array(
	        'username' => 'required|min:3|max:15|alpha_dash|unique:users',
	        'email' => 'email|reservation_cap:3',
	        'password' => 'required|between:4,11|confirmed',
	        'password_confirmation' => 'between:4,11'
    		)
    	);
	}


	//Interface requirements:
	public function getAuthIdentifier() 
	{
		return $this->getKey();
	}

	public function getAuthPassword() 
	{
		return $this->password;
	}

	public function getRememberToken()
	{
		return $this->remember_token;
	}
	
	public function setRememberToken($value)
	{
		$this->remember_token = $value;
	}
	
	public function getRememberTokenName()
	{
		return 'remember_token';
	}

}