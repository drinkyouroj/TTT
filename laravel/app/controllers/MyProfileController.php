<?php
class MyProfileController extends BaseController {

	public function __construct(
							NotificationRepository $not,
							FeedRepository $feed,
							ProfilePostRepository $profilepost,
							FavoriteRepository $save,
							PostRepository $post,
							FollowRepository $follow,
							CommentRepository $comment
							) {
		$this->not = $not;
		$this->feed = $feed;
		$this->profilepost = $profilepost;
		$this->save = $save;
		$this->post = $post;
		$this->follow = $follow;
		$this->comment = $comment;
	}

	protected $paginate = 12;


	//Normal Profile
	public function getPublicProfile($alias) {
		//First check to make sure that the profile doesn't belong to a logged in user.
		if( !Auth::guest() && Auth::user()->username == $alias) {
			//just redirect this user.
			return Redirect::to('myprofile');
		}

		//Load up the profile user.
		$profile_user = User::where('username',$alias)->first();

		if( Auth::check() ) {
			$user = Auth::user();//This is the 
			$is_following = $this->follow->is_following($user->id, $profile_user->id);
			$is_follower = $this->follow->is_follower($user->id, $profile_user->id);
			if($is_follower && $is_following) {
				$mutual = true;
			}
		} else {
			//load in the defaults anyway since we have to use with().
			$is_following = false;
			$is_follower = false;
			$mutual = false;
		}

		//grab the user's featured post
		if($profile_user->featured) {
			$featured = $this->post->findById($profile_user->featured);
		} else {
			$featured = false;
		}


		//Follower/Following count
		$follower_count = $this->follow->follower_count($profile_user->id);
		$following_count = $this->follow->following_count($profile_user->id);

		return View::make('v2/myprofile/profile')
					->with('myprofile', false)				//Make sure that we know that this is not yours.
					->with('profile_user', $profile_user)	//Profile user

					->with('is_following', $is_following)	//you are following this profile
					->with('is_follower', $is_follower)		//This profile follows you.
					->with('mutual', $mutual)				//Mutual?

					->with('following_count', $following_count)//Number of people this user is following
					->with('follower_count', $follower_count)//Number of people following this user

					//->with('featured', $featured)			//Featured Post
					//->with('collection', $collection)		//Actual posts and reposts.
					;

	}

	public function getMyProfile() {
		//The one and only user!
		$user = Auth::user();

		//Follower/Following count
		$follower_count = $this->follow->follower_count($user->id);
		$following_count = $this->follow->following_count($user->id);

		return View::make('v2/myprofile/profile')
					->with('myprofile', true)				//Make sure that we know that this is not yours.
					->with('profile_user', $user)			//Profile user

					->with('following_count', $following_count)//Number of people this user is following
					->with('follower_count', $follower_count)//Number of people following this user

					//->with('featured', $featured)			//Featured Post
					//->with('collection', $collection)		//Actual posts and reposts.
					;
	}

	public function getRestCollection ($type = 'all', $user_id = 0, $page = 1) {
		if(!$user_id) {
			$user_id = Auth::user()->id;
		}
		
		$types = array( 'all', 'post', 'repost' );

		if( in_array($type, $types) && $page > 0 ) {
			$collection = $this->profilepost->findByUserId($user_id, $type, $this->paginate, $page, true, false);
			return Response::json(
					array( 'collection' => $collection->toArray() ),
					200
				);
		} else {
			return Response::json(
				array( 'error' => 'invalid collection type and/or pagination' ),
				200
				);
		}
	}


	/**
	 *	Get the feed via rest call.
	 */
	public function getRestFeed ( $feed_type = 'all', $page = 1 ) {
		$user_id = Auth::user()->id;	
		$feed_types = array( 'all', 'post', 'repost' );

		// Make sure we have appropriate feed type...
		if ( in_array( $feed_type, $feed_types ) && $page > 0 ) {
			// Fetch the feed based on given params.
			$feed = $this->feed->find( $user_id, $this->paginate, $page, $feed_type, true );
			//return $feed;
			return Response::json(
				array( 'feed' => $feed->toArray() ),
				200
			);
		} else {
			return Response::json(
				array( 'error' => 'invalid feed type and/or pagination' ),
				200
				);
		}
		
	}

	public function getRestSaves ($page = 1) {
		$user_id = Auth::user()->id;
		$saves = $this->save->allByUserId($user_id, $this->paginate, $page, true);

		if(count($saves)) {
			return Response::json(
				array( 'saves' => $saves->toArray() ),
				200
				);
		} else {
			return Response::json(
				array( 'error' => 'No Saves' ),
				200
				);
		}
	}

	public function getRestDrafts($page = 1) {
		$user_id = Auth::user()->id;
		$drafts = $this->post->allDraftsByUserId($user_id, $this->paginate, $page, true);
		if(count($drafts)) {
			return Response::json(
				array( 'drafts' => $drafts->toArray() ),
				200
				);
		} else {
			return Response::json(
				array( 'error' => 'No Drafts' ),
				200
				);
		}
	}

	public function getRestComments($user_id = 0, $page = 1) {
		if(!$user_id) {
			$user_id = Auth::user()->id;
		}
		
		$comments = $this->comment->allByUserId($user_id, 8, $page, true);
		if(count($comments)) {
			return Response::json(
				array( 'comments' => $comments->toArray() ),
				200
				);
		} else {
			return Response::json(
				array( 'error' => 'No Comments' ),
				200
				);
		}
		
	}

	public function getRestFollowers($user_id, $page = 1) {
		$followers = $this->follow->restFollowers($user_id, 8,$page);
		if(count($followers)) {
			return Response::json(
				array('follow' => $followers->toArray()),
				200
				);
		}
	}


	public function getRestFollowing($user_id, $page = 1) {
		$following = $this->follow->restFollowing($user_id, 8,$page);
		if(count($following)) {
			return Response::json(
				array('follow' => $following->toArray()),
				200
				);
		}
	}

}