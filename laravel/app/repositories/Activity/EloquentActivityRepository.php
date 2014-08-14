<?php namespace AppStorage\Activity;

use Activity,DB, Request;

class EloquentActivityRepository implements ActivityRepository {

	public function __construct(
							Activity $activity
							) {
		$this->activity = $activity;
	}

	//Instance
	public function instance() {
		return new Activity;
	}


	//Create
	public function create($data) {
		$activity = self::instance();
		$activity->action_id = $data['action_id'];//notification from user
		$activity->user_id = $data['user_id']; 
		$activity->post_id = $data['post_id'];
		$activity->post_type = $data['post_type'];
		$activity->save();
	}

	//Read
	public function findByUserId(
								$user_id,
								$paginate = 12,
								$page = 1,
								$rest = false
								) {
		$query = $this->activity
					->where('user_id','=', $user_id)
					->orderBy('created_at', 'DESC')
					->take($paginate)
					->skip(($page-1)*$paginate);

		if($rest) {
			return $query->with('post.user')->get();
		} else {
			return $query->get();//get the activities
		}
		
	}
	
	//Read Multi
	public function all() {
		return $this->category->all();
	}
	

	//Update
	public function update($input) {

	}
	
	//Delete

	public function delete($data) {
		$this->activity
				->where('action_id', $data['action_id'])
				->where('user_id', $data['user_id'])
				->where('post_id', $data['post_id'])
				->where('post_type', $data['post_type'])
				->delete();
	}

	public function deleteAll($user_id, $post_id) {
			$this->activity
					->where('post_id', $post_id)
					->where('user_id', $user_id)
					->delete();
	}

}