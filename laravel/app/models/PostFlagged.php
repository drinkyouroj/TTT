<?php
//Marks which users have flagged a certain post.  The comment counter part is done in Mongo so its way less of an issue.
class PostFlagged extends Eloquent {
	
	//Just to be sure!
	protected $table = 'post_flagged';
	
	public function user() {
		return $this->belongsTo('User', 'user_id');
	}
	
	public function post() {
		return $this->belongsTo('Post', 'post_id');
	}	
}