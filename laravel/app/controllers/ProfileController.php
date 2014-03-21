<?php
class ProfileController extends BaseController {

	public function __construct() {

		
	}

	/**
	 * Gets the profile (including your own)
	 */
	public function getProfile($alias=false) {
		
		$is_following = false;
		$is_follower = false;
		$mutual = false;
		$activity = false;
		$follows = false;
		$reposts = false;
		$likes = false;
		$myposts = false;
		
		$post = false; //Featured Post for the user is stored as $post.
		$featured = false;
		
		
		//This is for other users. not yourself
		if($alias && $alias != Session::get('username')) {
			$user = User::where('username', '=', $alias)->first();
			$user_id = $user->id;//set the profile user id for rest of the session.
			
			//if the viewer is logged in.
			if(Session::get('user_id')) {
				$is_following = Follow::where('follower_id', '=', Session::get('user_id'))
								->where('user_id', '=', $user_id)
								->count();
				$is_follower = Follow::where('follower_id', '=', $user_id)
								->where('user_id', '=', Session::get('user_id'))
								->count();
				if($is_follower && $is_following) {
					$mutual = true;
				}
			}
			
			//Big col 12
			$fullscreen = true;

			//featured post.
			$post = Post::where('id', $user->featured)->where('published', 1);
			if($post->count())
			{
				$post = $post->first();
				$featured = new stdClass();
				$featured->post = $post;
				$featured->post_type = 'post';
			}

			$activity = ProfilePost::where('profile_id','=', $user_id)
							->orderBy('created_at', 'DESC')
							->get();//get the activities 	
			
		} else {
			//We're doing the user info loading this way to keep the view clean.
			$user_id = Auth::user()->id;
			$user = Auth::user();
			
			//Big col 9
			$fullscreen = false;
			
			//featured post.
			$post = Post::where('id', $user->featured);
			if($post->count())
			{
				$post = $post->first();
				$featured = new stdClass();//gotta fake a class sometimes.
				$featured->post = $post;
				$featured->post_type = 'post';
			} 
			
			//Activity is pulled from the user (current user) activity instead of ProfilePost (what anyone can see)
			$activity = Activity::where('user_id','=', $user_id)
							->orderBy('created_at', 'DESC')
							->get();//get the activities
							
			$myposts = ProfilePost::where('profile_id','=', $user_id)
							->orderBy('created_at', 'DESC')
							->get();//get the activities 	
		}

		return View::make('profile/index')
				->with('activity', $activity)
				->with('myposts', $myposts)
				->with('user', $user)
				->with('featured', $featured)
				->with('is_following', $is_following)//you are following this profile
				->with('is_follower', $is_follower)//This profile follows you.
				->with('mutual', $mutual)
				->with('fullscreen', $fullscreen);
	}

	/*
	 * Gives you the full notification history 
 	*/
	public function getNotifications() {
		$notifications = Notification::where('user_id', Session::get('user_id'))
									->take(20)//limit at 20 for now.  We'll AJAX in the rest.
									->get();
		
		$compiled = NotificationParser::parse($notifications);
		
		return View::make('profile/notifications')
				->with('notification_list', $compiled)
				->with('fullscreen', true);
	}
	
	/**
	 * Gives you your posts and your favorites.
	 */
	public function getMyPosts() {
		$myposts = ProfilePost::withTrashed()
							->where('profile_id', Auth::user()->id)
							->orderBy('created_at','DESC')
							->get();
		
		$user = User::where('id', Auth::user()->id)->first();
		
		if($user->featured != 0) {
			$post = Post::where('id', $user->featured)->first();
			$featured = new stdClass();
			$featured->post = $post;
			$featured->post_type = 'post';
		} else {
			$featured = false;
		}
		
		
		return View::make('profile/myposts')
				->with('myposts', $myposts)
				->with('user', $user)
				->with('featured', $featured)
				->with('fullscreen', true);
	}
	
	/**
	 * Place to have settings, etc.
	 */
	public function getSettings() {
		$user = User::where('id', Auth::user()->id)->first();
		
		return View::make('profile/settings')
				->with('fullscreen', true)
				->with('user', $user);
	}
	
}