<?php 

use Jenssegers\Mongodb\Model as Eloquent;
// use Jenssegers\Mongodb\Eloquent\SoftDeletingTrait;

/**
 *	FlaggedContent Model:
 *		type: String,		// 'post' or 'comment'
 *		post_id: ObjectId,  	// the post_id if type == 'post'
 *		comment_id: ObjectId	// the comment_id if type == 'comment'
 */
class FlaggedContent extends Eloquent {
	
	// use SoftDeletingTrait;
	// protected $dates = ['deleted_at'];
	
	protected $connection = 'mongodb';
	protected $collection = 'flagged_content';

	public static $rules = array(
		'type' => 'required|in:comment,post',
        'comment_id' => 'required_if:type,comment',
        'post_id' => 'required_if:type,post'
    );

	/**
	 *	Setup the relationship to a post
	 */
	public function post () {
		return $this->belongsTo('Post', 'post_id')->select('id', 'title','user_id','alias');
	}
	/**
	 *	Relationship to a comment
	 */
	public function comment () {
		return $this->belongsTo('MongoComment', 'comment_id');
	}

	public function validate ( $content ) {
		return Validator::make( $content, $rules );
	}
	
}
