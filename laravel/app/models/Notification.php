<?php 
class Notification extends Eloquent {
		
	//Just to be sure!
	protected $table = 'notifications';
	
	public function user()
	{
		return $this->belongsTo('User');
	}
	
	public function post()
	{
		return $this->belongsTo('Post');
	}
	
}
