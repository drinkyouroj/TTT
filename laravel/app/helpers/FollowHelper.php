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
	
	//Grabs all the users who are mutual.
	public static function mutual_list() {
		//folks  following you.
		$following = Follow::where('user_id', Session::get('user_id'))
						->select('follower_id')
						->get();
						
		//Another one of those dumb things about laravel.  I wonder if there is a way around this out.
		$fing = array();
		foreach($following as $k => $f) {
			$fing[$k] = $f->follower_id;
		}
		
		$mutual = Follow::whereIn('user_id', $fing)
						->where('follower_id', Session::get('user_id'))
						->get();
		return $mutual;
					
	}
	
	
}
