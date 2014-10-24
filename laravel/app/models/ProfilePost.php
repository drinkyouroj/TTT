<?php
/**
 * Profile Post is the user's "My Posts" or "Other People's Activity" piece
 * Also known as "Activity"
 */
use Illuminate\Database\Eloquent\SoftDeletingTrait;
class ProfilePost extends Eloquent {
	use SoftDeletingTrait;

	//Just to be sure!
	protected $table = 'profile_posts';
	protected $softDelete = true;
	protected $dates = ['deleted_at'];
	
	public function user() {
		return $this->belongsTo('User');
	}
	
	public function post() {
		$select = array(
						'id',
						'user_id',
						'title',
						'alias',
						'tagline_1',
						'tagline_2',
						'tagline_3',
						'story_type',
						'image',
						'published_at',
						'views',
						'nsfw'
						);

		//Let's make sure to add "view" count if the user is logged in.
		if(Auth::check()) {
			//I would rather not tie things down here this far, but there isn't an easier way right now.
			if(Request::segment(2) == Auth::user()->username || 
				Request::segment(1) == 'myprofile' ) 
			{
				array_push($select, 'views');
			}
		}

		return $this->belongsTo('Post')
					->select(
							$select
						)
					;
	}
	
}
