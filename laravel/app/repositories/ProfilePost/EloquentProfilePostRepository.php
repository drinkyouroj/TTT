<?php namespace AppStorage\ProfilePost;

use ProfilePost, DB, Request, Auth;

class EloquentProfilePostRepository implements ProfilePostRepository {

	public function __construct(ProfilePost $profilepost)
	{
		$this->profilepost = $profilepost;
	}

	//Instance
	public function instance() {
		return new ProfilePost;
	}

	//Create
	public function create($user_id, $post, $type) {
		$profilepost = self::instance();
		$profilepost->post_id = $post->id;
		$profilepost->profile_id = $user_id;
		$profilepost->user_id = $post->user->id;
		$profilepost->post_type = $type;
		$profilepost->save();
		return $profilepost;
	}

	//Delete
	public function delete($user_id, $post, $type) {
		$this->profilepost->where('profile_id', $user_id)
						->where('post_id', $post->id)
						->where('user_id', $post->user->id)
						->where('post_type', $type)
						->delete();
	}
	
}