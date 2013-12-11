<?php 
class Favorite extends Eloquent {
		
	//Just to be sure!
	protected $table = 'favorites';
	
	public function user()
	{
		return $this->belongsTo('User');
	}
	
	public function post()
	{
		return $this->belongsTo('Post');
	}
	
	
}
