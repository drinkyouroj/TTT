<?php 

use Jenssegers\Mongodb\Model as Eloquent;

class MongoPostView extends Eloquent {
	
	protected $connection = 'mongodb';
	protected $collection = 'postviews';
	protected $dates = array('created_at');

	/**
	 *	Setup the relationship of a comment to a post
	 */
	public function post() {
		return $this->belongsTo('Post', 'post_id')->withTrashed()->select('id', 'title','user_id','alias');
	}

}