<?php namespace AppLogic\FollowLogic;

use Session,
	Follow
	;

class FollowLogic {
	
	//Are you Follwing this person?
	public function is_following($user) {
		return Follow::where('follower_id', '=', Session::get('user_id'))
						->where('user_id', '=', $user)
						->count();

	}
	
	//Are they Following you?
	public function is_follower($user) {
		return Follow::where('follower_id', '=', $user)
						->where('user_id', '=', Session::get('user_id'))
						->count();
	}
	
	//You're following them and they're following you.
	public function mutual($user) {
		if(self::is_follower($user) && self::is_following($user)) {
			return true;
		} else {
			return false;
		}
	}
	
	//Grabs all the users who are mutual.  Hopefully we can replace this with Neo4J query in the future
	public function mutual_list() {
		//folks  following you.
		$following = Follow::where('user_id', Session::get('user_id'))
						->select('follower_id')
						->lists('follower_id');						
		
		//Mutual folks
		$mutual = Follow::whereIn('user_id', array_values($following))
						->where('follower_id', Session::get('user_id'))
						->get();
		return $mutual;
					
	}
}
