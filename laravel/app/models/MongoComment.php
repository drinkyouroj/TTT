<?php 

use Jenssegers\Mongodb\Model as Eloquent;

class MongoComment extends Eloquent {
	
	protected $connection = 'mongodb';
	protected $collection = 'comments';
	protected $dates = array('created_at');

	public function validate ( $input ) {
		$rules = array(
				'body' => 'Required'
		);
		return Validator::make( $input, $rules );
	}

	/**
	 *	Setup the relationship of a comment to a post
	 */
	public function post() {
		return $this->belongsTo('Post', 'post_id')->select('id', 'title','user_id','alias');
	}

}
