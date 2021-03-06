<?php
class ProfileController extends BaseController {

	public function __construct(
							PostRepository $post, 
							NotificationRepository $not,
							ProfilePostRepository $profilepost,
							FollowRepository $follow,
							ActivityRepository $activity
							) {
		$this->post = $post;
		$this->not = $not;
		$this->profilepost = $profilepost;
		$this->follow = $follow;
		$this->activity = $activity;
	}

	private $paginate = 12;
	
	/**
	 * Gets the profile (including your own)
	 * 	This function is a bit more complex than I would like right now.
	 * 	TODO Refactor into 2 different controller functions (something like UserProfile??)
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
		
		$guest = Auth::guest();

		//Below ensures that if the $alias is empty it will just redirect on not logged-in users
		if(Auth::guest() && !$alias ) {
			return Redirect::to('/');
		}
		
		//This is for other users. not yourself
		if( ($alias && $alias != Auth::user()->username) || Auth::guest() ) {
			$user = User::where('username', '=', $alias)->first();
			$user_id = $user->id;//set the profile user id for rest of the session.
			
			//if the viewer is logged in.
			if(Session::get('user_id')) {
				$is_following = $this->follow->is_following(Session::get('user_id'), $user_id);
								
				$is_follower = $this->follow->is_follower(Session::get('user_id'), $user_id);
				
				if($is_follower && $is_following) {
					$mutual = true;
				}
			}
			
			//Big col 12
			$fullscreen = true;

			//featured post.
			$post = $this->post->findById($user->featured);

			$activity = $this->profilepost
							 ->findByUserId($user->id, $this->paginate);
			
		} else {
			//We're doing the user info loading this way to keep the view clean.
			$user = Auth::user();
			$user_id = $user->id;

			//Big col 9
			$fullscreen = false;
			
			//featured post.
			$post = $this->post->findById($user->featured);
			
			//Activity is pulled from the user (current user) activity instead of ProfilePost (what anyone can see)
			$activity = $this->activity
							->findByUserId($user->id, $this->paginate);
							
			$myposts = $this->profilepost
							 ->findByUserId($user->id, $this->paginate);	
		}

		if($post != false)
		{	
			//This pretty much sets up an empty fake featured so we don't have an issue on thei view.
			//its definitely a hack-around
			$featured = new stdClass();
			$featured->post = $post;
			$featured->post_type = 'post';
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

		/**
		 * Profile Posts Autoloading.  This might move elsewhere.
		 */
		public function getRestProfile($alias = false) {
			
			if(Request::get('page')) {
				$page = abs(Request::get('page'));//just to be sure.
			} else {
				$page = 1;
			}
			
			if($alias && $alias != Session::get('username')) {
				$user = User::where('username', '=', $alias)->first();
				
				$activity = $this->profilepost
								->findByUserId(
									$user->id,
									$this->paginate,
									$page,
									true//REST
									);

				
			} else {
				$user = Auth::user();
				$activity = $this->activity
								->findByUserId(
									$user->id,
									$this->paginate,
									$page,
									true//REST
									);
			}
			
			if(!count($activity)) {
				return Response::json(
					array('error' => true),
					200
				);
			} else {
				return Response::json(
					$activity->toArray(),
					200
				);
			}
		}


	/*
	 * Gives you the full notification history 
 	*/
	public function getNotifications() {
		$compiled = $this->not->allDesc(Auth::user()->id);
		
		return View::make('profile/notifications')
				->with('notification_list', $compiled)
				->with('fullscreen', true);
	}
	
	/**
	 * Gives you your posts and your favorites.
	 */
	public function getMyPosts() {
		$myposts = $this->profilepost->findByUserId(
										Auth::user()->id,
										$this->paginate,
										false,
										true//trashed
									);
		
		$user = User::where('id', Auth::user()->id)->first();
		
		if($user->featured != 0) {
			$post = $this->post->findById($user->featured);
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