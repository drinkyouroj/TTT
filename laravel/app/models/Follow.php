<?php 
class Follow extends Eloquent {
		
	//Just to be sure!
	protected $table = 'follows';
	
	public function users()
	{
		return $this->belongsTo('User','user_id');
	}
	
	public function followers()
	{
		return $this->belongsTo('User','follower_id');
	}
	
}
