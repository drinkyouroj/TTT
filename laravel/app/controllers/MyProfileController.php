<?php
class MyProfileController extends BaseController {

	public function __construct(
							NotificationRepository $not,
							FeedRepository $feed,
							ProfilePostRepository $profilepost,
							FavoriteRepository $save,
							RepostRepository $repost,
							PostRepository $post,
							FollowRepository $follow,
							CommentRepository $comment,
							UserRepository $user,
							EmailRepository $email,
							EmailPrefRepository $emailpref ) {
		$this->not = $not;
		$this->feed = $feed;
		$this->profilepost = $profilepost;
		$this->save = $save;
		$this->repost = $repost;
		$this->post = $post;
		$this->follow = $follow;
		$this->comment = $comment;
		$this->user = $user;
		$this->email = $email;
		$this->emailpref = $emailpref;
	}

	protected $paginate = 12;


	//Normal Profile
	public function getPublicProfile($alias = false) {
		//First check to make sure that the profile doesn't belong to a logged in user.
		if( !Auth::guest() && Auth::user()->username == $alias) {
			//just redirect this user.
			return Redirect::to('myprofile');
		}

		//Load up the profile user. If admin or moderator, include soft deleted users
		$profile_user = User::where('username',$alias)->first();
		// If the user wasnt found and we are a moderator, check for the same alias among soft deleted users.
		if ( !is_object($profile_user) && Auth::check() && Auth::user()->hasRole('Moderator') ) {
			$profile_user = User::withTrashed()->where('username', $alias)->first();
		}

		// If we still dont have a user, just redirect back to profile page.
		if( !is_object($profile_user) ) {
			return Redirect::to('myprofile');
		}

		if( Auth::check() ) {

			$user = Auth::user();  //This is the current logged in user
			$is_following = $this->follow->is_following($user->id, $profile_user->id);
			$is_follower = $this->follow->is_follower($user->id, $profile_user->id);
			if($is_follower && $is_following) {
				$mutual = true;
			} else {
				$mutual = false;
			}

			if ( $user->hasRole('Admin') ) {
				// If admin, attach deleted posts of the current user to admin sidebar, also email prefs
				$deleted_posts = $this->post->allDeletedByUserId( $profile_user->id );
				$email_prefs = $this->emailpref->find( $profile_user->id );
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

		
		$deleted_posts = isset( $deleted_posts ) ? $deleted_posts : array();
		$email_prefs = isset( $email_prefs ) ? $email_prefs : false;

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
					->with('deleted_posts', $deleted_posts)
					->with('email_prefs', $email_prefs)
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

	/**
	 *	Update a users email
	 */
	public function postUpdateEmail() {
		$user = Auth::user();
		$password = Input::has('password') ? Input::get('password') : false;
		$new_email = Input::has('new_email') ? Input::get('new_email') : false;
		$error_message = '';
		$success = false;
		// Check for missing fields
		if ( !$password || !$new_email ) {
			$error_message = 'Oops! We are missing a field.';
		// Check for valid email format
		} else if ( !filter_var($new_email, FILTER_VALIDATE_EMAIL) ) {
			$error_message = 'It looks like you have provided an invalid email.';
		// Check that the User provided correct password.
		} else if ( Auth::validate(array( 'id' => $user->id, 'password' => $password )) ) {
			// Check that email is not their existing email.
			if ( $user->email == $new_email ) {
				$error_message = 'The email provided matches your current email.';
			// Check that the email does not already have three usernames attached.
			} else if ( $this->user->usernamesPerEmailCount( $new_email ) > 2 ) {
				$error_message = 'The provided email cannot be used for another account.';
			// We are good!
			} else {
				$success = true;
				
				/*
				if(strlen($user->email)) {
					$send_to = $user->email;
				} else {
					//no email to begin with.
					$send_to = $new_email;
				}
				*/
				//We figured out that this would be the only way that beta users could migrate off of @twothousandtimes.com emails they were assigned.
				$send_to = $new_email;

				//Update with the new email
				$user->updated_email = $new_email;
				$user->update_confirm = md5(date('YMDHiS').$new_email.rand(1,10));
				$user->save();
				AnalyticsLogic::createSessionEngagement( 'account-update-email' );

				//Send email to the new email.
				$email_data = array(
                    'from' => 'Sondry <no_reply@sondry.com>',
                    'to' => array($send_to),
                    'subject' => 'Change Your Email Address | Sondry!',
                    'plaintext' => View::make('v2/emails/change_email_plain')->with('user', $user)->render(),
                    'html'  => View::make('v2/emails/change_email_html')->with('user',$user)->render()
                    );

				$this->email->create($email_data);
			}
		// Wrong password
		} else {
			$error_message = 'Incorrect password! Try again.';
		}
		

		if ( $success ) {
			return Response::json( array( 'success' => true ), 200 );
		} else {
			return Response::json( array( 'error' => $error_message ), 200 );
		}
	}

	/**
	 *	Get the users notification.
	 *	returns 200:
	 *		notifications => array (array of notifications),
	 *		no_content => boolean (only true if there are no notifications on page 1)	
	 */
	public function getRestNotifications ( $page ) {
		$user = Auth::user();
		$page = $page;
		$paginate = 20;
		$notifications = $this->not->getByUserId( $user->id, $page, $paginate, false );
		$count = count( $notifications );

		if ( $page == 1 ) {
			AnalyticsLogic::createSessionEngagement( 'navigate', Request::path().'#notifications' );
		}	

		if ( $count > 0 ) {
			return Response::json( array( 'notifications' => $notifications->toArray(), 'page' => $page, 'paginate' => $paginate), 200);
		} else {

			if ( $page == 1 ) {
				// page 1 and 0 notifications => ie: no content at all
				return Response::json( array( 'no_content' => true ), 200);
			} else {
				// No more notifications -> reached the end
				return Response::json( array( 'notifications' => array() ), 200);
			}

		}
	}

	/**
	 *	Get the users Collection feed.
	 *	returns 200:
	 *		collection => array (the actual collection data),
	 *		error => string (invalid request parameters),
	 *		no_content => boolean (only true if there is no content to be returned on page 1)
	 */
	public function getRestCollection ($type = 'all', $user_id = 0, $page = 1) {
		if(!$user_id) {
			$user_id = Auth::user()->id;
		}

		if ( $page == 1 && $type == 'all' ) {
			AnalyticsLogic::createSessionEngagement( 'navigate', Request::path().'#collection' );
		}
		
		$types = array( 'all', 'post', 'repost' );

		if( in_array($type, $types) && $page > 0 ) {
			$collection = $this->profilepost->findByUserId($user_id, $type, $this->paginate, $page, true, false);
			
			if ( count( $collection ) == 0 && $page == 1 ) {
				// If we are on page 1 and there is no content, send back empty content message
				return Response::json(
					array( 'no_content' => true ),
					200
				);
			} else {
				return Response::json(
					array( 'collection' => $collection->toArray() ),
					200
				);
			}
		} else {
			return Response::json(
				array( 'error' => 'invalid collection type and/or pagination' ),
				200
				);
		}
	}

	public function getRestFeatured ($post_id) {
		if($post_id) {
			$featured  = $this->post->findById($post_id, true, array('user'));
			if(is_object($featured) && isset($featured->id)) {
				$featured->excerpt = substr(strip_tags($featured->body),0,100);
				return Response::json(
						array('featured' => $featured->toArray() ),
						200
					);
			} else {
				return Response::json(
						array('featured' => false),
						200
					);
			}
		} 
	}

	public function getRestPostDelete($post_id) {
		if($post_id) {
			$user = Auth::user();
			$post_id = intval($post_id);
			$data = array(
					'user_id' => $user->id,
					'post_id' => $post_id,
					'post_type' => 'post'
				);
			$this->profilepost->delete($data);

			//let's make sure that this belongs to the user.
			$post = $this->post->findById($post_id, 'any');

			if($post->user_id == $user->id) {
				$this->post->delete($post_id);
			}

			AnalyticsLogic::createSessionEngagement( 'post-delete' );
			
			return Response::json(
					array('success' => true),
					200
				);
		}
	}

	public function getRestRepostDelete ($post_id) {
		if($post_id) {
			$user = Auth::user();
			$data = array(
					'profile_id' => $user->id,//get rid of this repost from this user's profile
					'post_id' => $post_id,
					'post_type' => 'repost'
				);
			$this->profilepost->delete($data);

			$this->repost->delete($user->id, $post_id);

			return Response::json(
					array('success' => true),
					200
				);
		}
	}

	//Sets the user's featured id.
	public function postRestFeatured ($post_id) {
		if($post_id) {
			$user = Auth::user();
			$user->featured = $post_id;
			$user->save();
			return Response::json(
					array('success'=> 'true'),
					200
				);
		} else {
			return Response::json(
					array('success'=> 'false'),
					200
				);
		}
	}


	/**
	 *	Get the users feed.
	 *	returns 200:
	 *		collection => array (the actual feed data),
	 *		error => string (invalid request parameters),
	 *		no_content => string (message to be displayed if there is nothing in feed)
	 */
	public function getRestFeed ( $feed_type = 'all', $page = 1 ) {
		$user_id = Auth::user()->id;	
		$feed_types = array( 'all', 'post', 'repost' );

		// Make sure we have appropriate feed type...
		if ( in_array( $feed_type, $feed_types ) && $page > 0 ) {
			// Fetch the feed based on given params.
			$feed = $this->feed->find( $user_id, $this->paginate, $page, $feed_type, true );
			
			if ( count( $feed ) == 0 && $page == 1 ) {
				return Response::json(
					array( 'no_content' => true ),
					200
				);
			} else {
				return Response::json(
					array( 'feed' => $feed->toArray() ),
					200
				);
			}
		} else {
			return Response::json(
				array( 'error' => 'invalid feed type and/or pagination' ),
				200
				);
		}
		
	}

	/**
	 *	Get the users saves.
	 *	returns 200:
	 *		collection => array (the actual saves data),
	 *		error => string (invalid request parameters),
	 *		no_content => string (message to be displayed if there is nothing in saves)
	 */
	public function getRestSaves ($page = 1) {
		$user_id = Auth::user()->id;
		$saves = $this->save->allByUserId($user_id, $this->paginate, $page, true);

		if ( count( $saves ) == 0 && $page == 1 ) {
			return Response::json(
				array( 'no_content' => true ),
				200
			);
		} else if( count( $saves ) ) {
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
		public function getRestSaveDelete ($post_id) {			
			if($post_id) {
				$user_id = Auth::user()->id;
				$this->save->delete($user_id, $post_id);
				return Response::json(
						array( 'success' => true),
						200
					);
			}
		}

	public function getRestDrafts($page = 1) {
		$user_id = Auth::user()->id;
		$drafts = $this->post->allDraftsByUserId($user_id, $this->paginate, $page, true);
		if ( count( $drafts ) == 0 && $page == 1 ) {
			return Response::json(
				array( 'no_content' => true ),
				200
			);
		} else if( count( $drafts ) ) {
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

	public function getRestSettings() {
		if(Auth::check()) {
			$user = Auth::user();
			if($this->emailpref->exists($user->id, true)) {
				$emailpref = $this->emailpref->exists($user->id);
			} else {
				//brand spanking new user.
				$data = array();
				$data['user_id'] = $user->id;
				$emailpref = $this->emailpref->create($data);
			}

			return Response::json(
					array(
						'emailpref' => $emailpref->toArray(),
						'name' => $user->name,
						'website' => $user->website
						),
					200
				);
		}
	}

		public function postRestEmailPref() {
			$data = array();
			$data['user_id'] = Auth::user()->id;
			$data['views'] = Request::get('views',0);
			$data['comment'] = Request::get('comment',0);
			$data['reply'] = Request::get('reply',0);
			$data['like'] = Request::get('like',0);
			$data['repost'] = Request::get('repost',0);
			$data['follow'] = Request::get('follow',0);
			$this->emailpref->update($data);
			return Response::json(
					array('result' => $data ),
					200
				);
		}

		public function postRestProfile() {
			if( Auth::check() ) {
				$profile['id'] = Auth::user()->id;
				$profile['name'] = Request::get('name','');
				$profile['website'] = Request::get('website','');
				$result = $this->user->updateProfile($profile);
				return Response::json(
						array('success' => $result),
						200
					);
			} else {
				return Response::json(
						array('success' => false),
						200
					);
			}
		}

	public function getRestComments($user_id = 0, $page = 1) {
		if(!$user_id) {
			$user_id = Auth::user()->id;
		}
		
		$comments = $this->comment->findByUserId($user_id, 8, $page, true);
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
		$followers = $this->follow->restFollowers($user_id, 24, $page);
		if(count($followers)) {
			return Response::json(
				array('follow' => $followers->toArray()),
				200
				);
		} else {
			return Response::json(
				array('follow' => array() ),
				200
				);
		}
	}


	public function getRestFollowing($user_id, $page = 1) {
		$following = $this->follow->restFollowing($user_id, 24, $page);
		if(count($following)) {
			return Response::json(
				array('follow' => $following->toArray()),
				200
				);
		} else {
			return Response::json(
				array('follow' => array() ),
				200
				);
		}
	}

	//This is for the avatar upload.
	public function postAvatar( ) {
		$file_name = Request::get('image');
		$input = array('image' => $file_name);

		//validation that the file infact is an image
		$rules = array(
			'image' => 'required'
		);

		$validator = Validator::make($input, $rules);

		//kill two birds in one.
		if ($validator->fails() || Auth::guest() ) {
			return Response::json(
					array('error' => 'You are not logged in or you did not send anything'),
					200
				);
		} else {
			//save the image as part of the usermodel.  We'll need to put this in the User repo later.
			$user = Auth::user();
			$user->image = $file_name;
			$user->update();

			return Response::json(
					array(
						'image' => $file_name
						),
					200
				);
		}
	}

}