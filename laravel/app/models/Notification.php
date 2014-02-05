<?php 
class Notification extends Eloquent {
		
	//Just to be sure!
	protected $table = 'notifications';
	
	//Below is tied to action id since notifications is from the person causing the action
	public function user()
	{
		return $this->belongsTo('User', 'action_id');
	}
	
	public function post()
	{
		return $this->belongsTo('Post');
	}
	

	
}
