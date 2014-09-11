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
	public function create($data) {
		$profilepost = self::instance();
		$profilepost->post_id = $data['post_id'];
		$profilepost->profile_id = $data['profile_id'];
		$profilepost->user_id = $data['user_id'];
		$profilepost->post_type = $data['post_type'];
		$profilepost->save();
		return $profilepost;
	}

	public function findByUserId(	
									$user_id,
									$type,
									$paginate = 12, 
									$page = 1, 
									$rest = false,
									$trashed = false
								) {

		$query = $this->profilepost->where('profile_id', $user_id)									
									->orderBy('created_at', 'DESC')
									->skip(($page-1)*$paginate)
									->take($paginate);
		if($type != 'all') {
			$query = $query->where('post_type', $type);
		} else {
			$query = $query->whereIn('post_type', array('post', 'repost'));
		}

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
	public function delete($data) {
		$rowsaffected = $this->profilepost->where('profile_id', $data['profile_id'])
						->where('post_id', $data['post_id'])
						->where('post_type', $data['post_type'])
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