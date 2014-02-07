<?php
/**
 * Post View stores who's viewed the post or not.
 * 	(Good candidate for mongo)
 */

class PostView extends Eloquent {
		
	//Just to be sure!
	protected $table = 'post_views';

	public function user() {
		return $this->belongsTo('User');
	}
	
	public function post() {
		return $this->belongsTo('Post');
	}
	
}
