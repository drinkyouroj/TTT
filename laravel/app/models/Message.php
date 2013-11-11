<?php 
class Message extends Eloquent {
		
	//Just to be sure!
	protected $table = 'messages';
	
	public function to()
	{
		return $this->belongsTo('User', 'to_uid');
	}
	
	public function from()
	{
		return $this->belongsTo('User', 'from_uid');
	}
	
}
