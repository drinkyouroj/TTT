<?php 
class Featured extends Eloquent {
		
	//Just to be sure!
	protected $table = 'featured';
	
	public function user()
	{
		return $this->belongsTo('User');
	}
	
	public function post()
	{
		return $this->belongsTo('Post');
	}
	
}