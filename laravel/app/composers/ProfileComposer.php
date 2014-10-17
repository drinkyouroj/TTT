<?php
class ProfileComposer {
	public function compose($view) {
		$alias = Request::segment(2);
		
		if($alias && $alias != Session::get('username') ) {//This is for other users. not yourself
			$user = User::where('username', '=', $alias)->first();
			// If you are mod or admin, you may view soft deleted user profiles as well
			if ( !is_object($user) && Auth::check() && Auth::user()->hasRole('Moderator') ) {
				$user = User::withTrashed()->where('username', '=', $alias)->first();
			}
			$user_id = $user->id;//set the profile user id for rest of the session.
		} else {
			//We're doing the user info loading this way to keep the view clean.
			$user_id = Session::get('user_id');
			
			//This isn't most ideal, but let's just place the banned detector here
			if(User::where('id', $user_id)->where('banned', true)->count()) {
				//this guy's session will terminate right as he/she clicks on any profile action.
				Auth::logout();
				return Redirect::to('/banned');
			}
		}

		$follow = App::make('AppStorage\Follow\FollowRepository');

		$followers = $follow->follower_count($user_id);
		$following = $follow->following_count($user_id);
		
		$view->with('followers', $followers)
			 ->with('following', $following);
	}
}