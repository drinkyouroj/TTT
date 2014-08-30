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
		return $this->follow->where('user_id', $user_id)
					 ->where('follower_id', $follower_id)
					 ->count();
	}

	public function followersByUserId($user_id) {
		return $this->follow->where('user_id', $user_id)->get();
	}

	public function followingByUserId($user_id) {
		return $this->follow->where('follower_id', $user_id)->get();
	}

	//Move below code to User Repository later.
	//Below code will need to have autoload attached to it later.
	//Grabs people who are following a user id 
	public function jsonFollowers($user_id) {
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

	public function jsonFollowing($user_id) {
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

	//better than the stuff above.
	public function restFollowers($user_id, $paginate = 8, $page = 1 ) {
		return $this->follow
					->where('user_id', $user_id)
					->select('user_id', 'follower_id')
					->skip(($page-1)*$paginate)
					->take($paginate)
					->with('followers')//user type
					->get()
					;
	}


	public function restFollowing($user_id, $paginate = 8, $page = 1 ) {
		return $this->follow
					->where('follower_id', $user_id)
					->select('user_id', 'follower_id')
					->skip(($page-1)*$paginate)
					->take($paginate)
					->with('following')//user type
					->get()
					;
	}

	public function follower_count($user_id) {
		return $this->follow->where('user_id', $user_id)->count();
	}

	public function following_count($user_id) {
		return $this->follow->where('follower_id', $user_id)->count();
	}

	public function is_follower($my_id, $other_id) {
		return $this->follow->where('follower_id', $other_id )
								->where('user_id', $my_id )
								->count();
	}

	public function is_following($my_id, $other_id) {
		return $this->follow->where('follower_id', $my_id)
								->where('user_id', $other_id)
								->count();
	}

	public function mutual($my_id, $other_id) {
		if( self::is_follower($my_id, $other_id) && self::is_following($my_id, $other_id) ) 
		{
			return true;
		} else {
			return false;
		}
	}

	//Grabs all the users who are mutual.  Hopefully we can replace this with Neo4J query in the future
	public function mutual_list($my_id) {
		//folks  following you.
		$following = $this->follow
						->where('user_id', $my_id)
						->select('follower_id')
						->lists('follower_id');						
		
		//Mutual folks
		$mutual = $this->follow
						->whereIn('user_id', array_values($following))
						->where('follower_id', $my_id)
						->get();
		return $mutual;
	}
	
	//Delete
	public function delete($user_id, $follower_id) {
		$this->follow->where('user_id', $user_id)
			  ->where('follower_id', $follower_id)
			  ->delete();
	}
	
}