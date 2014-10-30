<?php 
class Like extends Eloquent {
		
	//Just to be sure!
	protected $table = 'likes';
	
	public function user()
	{
		return $this->belongsTo('User')->withTrashed();
	}
	
	public function post()
	{
		return $this->belongsTo('Post');
	}
	
}
