<?php namespace AppStorage\Follow;

use DB,
	Follow,
	Request
	;

class EloquentFollowRepository implements FollowRepository {

	public function __construct(Follow $follow)
	{
		$this->follow = $follow;
	}

	//Instance
	public function instance() {
		return new Follow;
	}

	public function create($user_id, $follower_id) {
		$follow = new Follow;
		$follow->user_id = $other_user_id;
		$follow->follower_id = $my_user_id;//Gotta be from you.
		$follow->save();
	}

	//Exists
	public function exists($user_id, $follower_id) {
		return Follow::where('user_id', $user_id)
					 ->where('follower_id', $follower_id)
					 ->count();
	}
	
	//Delete
	public function delete($user_id, $follower_id) {
		Follow::where('user_id', $user_id)
			  ->where('follower_id', $follower_id)
			  ->delete();
	}
	
}