<?php
use Jenssegers\Mongodb\Model as Eloquent;

class EmailPref extends Eloquent {
	
	protected $connection = 'mongodb';
	protected $collection = 'emailprefs';
	protected $dates = array('created_at');

	public function user() {
		return $this->belongsTo('User', 'user_id')->select('id', 'username', 'email');
	}

}