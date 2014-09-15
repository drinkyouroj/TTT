<?php 

use Jenssegers\Mongodb\Model as Eloquent;

class MongoComment extends Eloquent {
	
	protected $connection = 'mongodb';
	protected $collection = 'comments';
	protected $dates = array('created_at');

	public function validate ( $input ) {
		$rules = array(
				'body' => 'required|min:1|max:5000',
				'post_id' => 'required',
				'slug' => 'required',
				'full_slug_asc' => 'required',
				'full_slug_desc' => 'required',
				'author' => 'required',
				'depth' => 'required|integer|min:0',
				'likes' => 'array',
				'flags' => 'array'
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
