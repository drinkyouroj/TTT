<?php
/**
 * Profile Post is the user's "My Posts" or "Other People's Activity" piece
 * Also known as "Activity"
 */

class ProfilePost extends Eloquent {
		
	//Just to be sure!
	protected $table = 'profile_posts';
	protected $softDelete = true;

	public function user() {
		return $this->belongsTo('User');
	}
	
	public function post() {
		return $this->belongsTo('Post')
					->select(
							'id',
							'user_id',
							'title',
							'alias',
							'tagline_1',
							'tagline_2',
							'tagline_3',
							'story_type',
							'image',
							'published_at'
							)
					;
	}
	
}
