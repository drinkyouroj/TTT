<?php
/**
 * Profile Post is the user "activity" piece
 */

class ProfilePost extends Eloquent {
		
	//Just to be sure!
	protected $table = 'profile_posts';

	public function user() {
		return $this->belongsTo('User');
	}
	
	public function post() {
		return $this->belongsTo('Post');
	}
	
}
