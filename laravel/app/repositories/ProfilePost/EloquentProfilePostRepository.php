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

	public function findByUserId(	
									$user_id,
									$paginate = 12, 
									$page = 1, 
									$rest = false,
									$trashed = false
								) {

		$query = $this->profilepost->where('profile_id', $user_id)
									->orderBy('created_at', 'DESC')
									->skip(($page-1)*$paginate)
									->take($paginate);

		if($trashed) {
			return $query->withTrashed()->get();
		}

		if($rest) {
			return $query->with('post.user')->get();
		} else {
			return $query->get();
		}
	}


	//Delete (this is a soft delete)
	public function delete($user_id, $post, $type) {
		$this->profilepost->where('profile_id', $user_id)
						->where('post_id', $post->id)
						->where('user_id', $post->user->id)
						->where('post_type', $type)
						->delete();
	}

	//This correspondes to teh post unpublish scenario .
	public function publish($user_id, $post_id) {
		$this->profilepost->onlyTrashed()
						->where('post_id', $post_id)
						->where('profile_id', $user_id)//This is based on who is affected.
						->restore();
	}
	
}