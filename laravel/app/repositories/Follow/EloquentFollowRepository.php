<?php namespace AppStorage\Follow;

use DB,
	Follow,
	User,
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

	//Create a follow relationship
	public function create($user_id, $follower_id) {
		$follow = self::instance();
		$follow->user_id = $user_id;
		$follow->follower_id = $follower_id;//Gotta be from you.
		$follow->save();
	}

	//See if a Follow relationship exists
	public function exists($user_id, $follower_id) {
		return Follow::where('user_id', $user_id)
					 ->where('follower_id', $follower_id)
					 ->count();
	}


	//Move below code to User Repository later.
	//Below code will need to have autoload attached to it later.
	//Grabs people who are following a user id 
	public function followers($user_id) {
		$followers = User::where('id',$user_id)
					->where('banned',0)
					->select('id', 'username')
					->with('followers.followers')
					->get()
					->toArray();//gets only the followers array

		//Below is not 100% ideal, but since this ORM isn't perfect, we'll have to deal with it.
		$data = array();
		foreach($followers[0]['followers'] as $value) {
			if(isset($value['followers'])) {
				array_push($data, $value['followers']);
			}
		}
		return $data;
	}

	public function following($user_id) {
		$following = User::where('id', $user_id)
					->where('banned',0)
					->select('id', 'username')
					->with('following.following')
					->get()
					->toArray();

		$data = array();

		foreach($following[0]['following'] as $value) {
			if(isset($value['following'])) {
				array_push($data, $value['following']);
			}
		}
		return $data;
	}

	
	//Delete
	public function delete($user_id, $follower_id) {
		Follow::where('user_id', $user_id)
			  ->where('follower_id', $follower_id)
			  ->delete();
	}
	
}