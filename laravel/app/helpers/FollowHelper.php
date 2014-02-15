<?php


//WOrk on making a follow /following checker here!
class FollowHelper {
	
	//pass the other person.  Are you follwing this person?
	public static function is_following($user) {
		return Follow::where('follower_id', '=', Session::get('user_id'))
						->where('user_id', '=', $user)
						->count();

	}
	
	//Pass on the ohter person.  
	public static function is_follower($user) {
		return Follow::where('follower_id', '=', $user)
						->where('user_id', '=', Session::get('user_id'))
						->count();
	}
	
	
	public static function mutual($user) {
		if(self::is_follower($user) && self::is_following($user)) {
			return true;
		} else {
			return false;
		}
	}
	
}
