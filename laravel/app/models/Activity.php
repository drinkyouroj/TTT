<?php
class Activity extends Eloquent {
	
	//Just to be sure!
	protected $table = 'activities';
	
	public function user() {
		return $this->belongsTo('User', 'action_id');//Gotta be about the person doing the act.
	}
	
	public function post() {
		return $this->belongsTo('Post', 'post_id');
	}
	
}
