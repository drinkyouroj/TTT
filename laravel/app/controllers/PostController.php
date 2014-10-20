<?php

use \Carbon\Carbon;

class PostController extends BaseController {

	public function __construct(
							PostRepository $post,
							RepostRepository $repost,
							FavoriteRepository $favorite,
							LikeRepository $like,
							FollowRepository $follow,
							PostViewRepository $postview,
							ProfilePostRepository $profilepost,
							ActivityRepository $activity,
							CommentRepository $comment,
							FeedRepository $feed,
							FeaturedRepository $featured,
							SearchRepository $search,
							PostFlaggedRepository $post_flagged ) {
		$this->post = $post;
		$this->repost = $repost;
		$this->favorite = $favorite;
		$this->like = $like;
		$this->follow = $follow;
		$this->postview = $postview;
		$this->profilepost = $profilepost;
		$this->activity = $activity;
		$this->comment = $comment;
		$this->feed = $feed;
		$this->featured = $featured;
		$this->search = $search;
		$this->post_flagged = $post_flagged;
	}

	public function getIndex() {
		$post = $this->post->random();
		return self::getPost($post->alias);
	}
	
    /**
     * Get Post
     */
    public function getPost ( $alias )
    {
    	if(Cache::has($alias) && !Session::get('admin') ) {
        	$post = Cache::get($alias);
        } else {
        	//real query
        	$post = $this->post->findByAlias( $alias );

        	$expiresAt = Carbon::now()->addMinutes(10);
			Cache::put($alias,$post,$expiresAt);
        }
        // Check that the post even exists (includes soft deleted).
		if ( !is_object($post) ) {
			return Redirect::to('/');
		}
		
		$user = Auth::check() ? Auth::user() : false;
		$user_id = $user != false ? $user->id : false;
		$is_mod = $user != false ? $user->hasRole('Moderator') : false;
		$is_admin = $user != false ? $user->hasRole('Admin') : false;
		
		// 1. admin can view any post (deleted or not)
		// 2. anyone may view a post that is published
		// 3. user may view post that is draft => only if they are the author
		if (  $is_admin || (!$post->trashed() && ($post->published || $post->user_id == $user_id)) ) {
			//Post exists and (is published OR draft is owned by appropriate user)
						
			if( !isset($post->user->username) ) {
				$user_id = 1;//1 is the loneliest number! (aka user nobody)
				$post->user = User::find(1);
			} else {
				$user_id = $post->user->id;
			}
			
			//Logged in.
			if( $user != false ) {
				
				$my_id = Auth::user()->id;//My user ID
				$is_following = $this->follow->is_following($my_id,$user_id);
				$is_follower = $this->follow->is_follower($my_id,$user_id);
				$liked = $this->like->has_liked($my_id, $post->id);
				$favorited = $this->favorite->has_favorited($my_id, $post->id);
				$reposted = $this->repost->has_reposted($my_id, $post->id);
				$has_flagged = $this->post_flagged->exists( $my_id, $post->id );
			} else {
				//DEFAULTS for not logged in users
				$my_id = false;
				$is_follower = false;
				$is_following = false;
				$liked = false;
				$favorited = false;
				$reposted = false;
				$has_flagged = false;
			}
			
			//Add the fact that the post has been viewed if you're not the owner and you're logged in.
			if( $user_id != $my_id ) {
				$session_id = Session::get('current_session');//set in filters
				$exists = $this->postview->exists($session_id, $post->id);

				//If the record doesn't exist, increment on the post view count and also add to the "viewed" in PostView
				if( !$exists ) {
					$view = array(
						'session_id' => $session_id,
						'post_id' => $post->id
						);
					$this->postview->create($view);
					$this->post->incrementView($post->id);//increment on this post.
				}
			}

			$view = View::make( 'v2/posts/post' )
						->with('post', $post)
						->with('is_following', $is_following)//you are following this profile
						->with('is_follower', $is_follower)//This profile follows you.
						->with('bodyarray', PostLogic::divide_text($post->body, 1500))//This divides the body text into parts so that we can display them in multiple steps.
						->with('liked', $liked)
						->with('favorited', $favorited)
						->with('reposted', $reposted)
						->with('has_flagged', $has_flagged)
						->with('is_editable', $this->post->checkEditable($post->published_at))
						;

			// If we need to populate the comment form ( after user signup )
			if ( Session::has( 'restore_comment' ) ) {
				$restore_comment = Session::get( 'restore_comment' );
				Session::forget( 'restore_comment' );
				$view->with( 'restore_comment', $restore_comment );
			}
			// If admin or mod, we need some additional info for the view
			if ( $is_mod || $is_admin ) {
				$featured = $this->featured->findByPostId( $post->id );
				$view->with( 'featured', $featured );
			}

			// If this is a bot, display all the comments in the view for seo purposes
			if ( $this->bot_detected() ) {
				$comments = $this->comment->findAllByPostId( $post->id );
				$view->with( 'comments', $comments );
			}

	        return $view;

		} else {
			return Redirect::to('/');
		}
    }
	

	/**
	 * Post form
	 */
	public function getPostForm($id=false) {
		Session::put('post','');
		
		if($id) {
			//EditPost
			$post = $this->post->findById($id);

			if ( !($post instanceof Post) || Auth::user()->id != $post->user_id ) {
				return View::make('v2/errors/error');
			}
			if($post->published) {
				if(!$this->post->checkEditable($post->published_at)) {
					return View::make('v2/errors/error');
				}
			}

			return View::make('v2/posts/post_form')//Edit form only has to account for the text.  Not the entire listing.
					->with('post', $post)
					->with('edit', true);
		} else {
			//Gotta put in a query here to see if the user submitted something in the last 10 minutes
			$post = $this->post->lastPostUserId(Auth::user()->id,1);
			
			if(isset($post->id)) {
				//not an admin and 10min has not passed since your last post.
				if(!Session::get('admin') && strtotime(date('Y-m-d H:i:s', strtotime('-10 minutes'))) <= strtotime($post->created_at)  ){

					//Gotta make a new view for that.
					return View::make('v2/errors/post-limit-error');
				}
			}
					
			return View::make('v2/posts/post_form')->with('edit', false);
		}
		
	}
	
	/**
	 * Save the Post Form
	 * 	Where the Posts are born! (and edited)
	 * 
	 */
	public function postPostForm() {
		self::savePost(false);
	}

	/**
	 *	Parse the post body. 
	 *	Returns the post in an array of 'pages'. 
	 *	Note: I also attached the auther (current logged in user) to the response
	 *		just so I have their username when showing the preview of the post (at
	 *		this time this is only used for post preview).
	 */
	public function parsePostBody() {
		$post_body = Input::has('body') ? Input::get('body') : '';
		$body_array = PostLogic::divide_text($post_body, 1500);
		$author = Auth::user()->username;
		return Response::json( array( 
			'body_array' => $body_array,
			'author' => $author
			), 200 );
	}

	/**
	 * The REST version
	 * Sends back a JSON Response instead of a Redirect.
	*/
	public function postSavePost() {
		return self::savePost(true);
	}

		//Below assumes that the validation has passed.  It can be used to update or to save the post.
		private function savePost($rest=false) {			
			$request = Request::all();
			$autosave = isset($request['autosave']) ? $request['autosave'] : false ;
			$check_post = false;
			$previously_published = false;

			if( isset($request['id']) ) {
				$check_post = $this->post->findById( $request['id'] );
			}

			//The post exists.
			if(!empty($check_post->id) ) {

				// FIRST THINGS FIRST! Is the logged in user the author of the post?
				if ( Auth::user()->id != $check_post->user_id ) {
					return Response::json( array('error' => 'You put your fingers in the wrong hole...'), 405 );
				}

				//If the post exists, let's save its previous published state.
				$previously_published = $check_post->published;

				//THIS is the update scenario.
				//let's double check that this ID exists and belongs to this user.			
				$new = false;
				
				if($check_post->published) {
					//Now that we know this exists, let's check to see if its been more than 3 days since it was initially posted.
					if(!$this->post->checkEditable($check_post->published_at)) {
						//more than 72 hours has passed since the post was created.
						//TODO Maybe I should have this go somewhere more descriptive??
						return Response::json(
								array('error' => '72'),
								405//method not allowed
							);

					}
				}

				//If the post is published and the user is trying to set it as a draft.
				if($previously_published && $request['draft']) {
					return Response::json(
							array('error' => 'pub2draft'),
							405//method not allowed
						);
				}

				$post = self::rest_input($new, $check_post);
				
				$validator = $post->validate($post->toArray(),$check_post->id);//validation takes arrays.  Also if this is an update, it needs an id.


			} else {
				//New Post.
				$new = true;
				//Checking to see if this is an new post being applied by a punk
				$last_post = $this->post->lastPostUserId(Auth::user()->id, 1);
				
				if( isset($last_post->id) && 
					$new && 
					!Session::get('admin') &&
					strtotime(date('Y-m-d H:i:s', strtotime('-10 minutes'))) <= strtotime($last_post->created_at))
				{
					//Nice try punk.  Maybe I should have this go somewhere more descriptive.
					return Response::json(
							array('error' => '10'),
							405//method not allowed
						);
				}

				$post = self::rest_input($new, false);
				
				$validator = $post->validate($post->toArray(),false);//no
			}
			
			if($validator->passes()) {//Successful Validation
				
				$user = Auth::user();

				if($new) {
					//post is new.
					$post->save();
								
					//no featured for this user? set this new post as featured.
					if($user->featured == false && $post->draft == false) {
						User::where('id', Auth::user()->id)
							->update(array('featured' => $post->id));
					}
					
				} else {
					//Gotta put in a thing here to get rid of all relations
					$post->categories()->detach();//Pivot! Pivot! 

					$post->update();
				}

				//If this is the web server upload this content to the CDN.
				/*
				if(App::environment() == 'web') {
					$file = OpenCloud::upload('Images', public_path().'/uploads/final_images/'.$post->image, $post->image);
				}
				*/
				if (Input::has('category')) {
					//Gotta save the categories pivot
					foreach(Input::get('category') as $k => $category) {
						if($k <= 1) {//This will ensure that no more than 2 are added at a time.
							$post->categories()->attach($category);
						} else {
							break;//let's not waste processes
						}
					}
				}

				
				//if the post becomes published. (published now, wasn't published before.)
				if($post->published && !$previously_published) {
					
					//Put it into the profile post table (my posts or what other people see as your activity)
					$new_profilepost = array(
						'profile_id' => $user->id,
						'user_id' => $user->id,
						'post_id' => $post->id,
						'post_type' => 'post'
						);
					$profilepost = $this->profilepost->create($new_profilepost);
					
					
					//Also save this data to your own activity
					$new_activity = array(
						'user_id' => $user->id,//who's profile is this going to?
						'action_id' => $user->id,//Who's doing the action?
						'post_id' => $post->id,
						'post_type' => 'post'//new post!
						);
					$this->activity->create($new_activity);

					//QUEUE
					//Add to follower's notifications.
					Queue::push('UserAction@newpost', 
								array(
									'post_id' => $post->id,
									'user_id' => $user->id,
									'username' => $user->username
									)
								);

					// Add to search db
					$this->search->updatePost( $post );
				}

				if($new) {
					$json_result = 'create';
				} else {
					$json_result = 'update';
				}

				//this is to let the profile page know what happened after the redirect.
				if($autosave == false) {
					if($post->draft) {
						Session::put('post','draft');
					} else {
						Session::put('post','published');
					}
				}
				

				return Response::json(
						array(
							'result' => $json_result,
							'id'	=> $post->id,
							'alias' => $post->alias
							),
						200 //happy happy joy joy!
					);

				
			} else {//Failed Validation on serverside should just redirect no matter what.
				return Response::json(
					array('error' => '405'),//make sure that they are redirected. They might be trying to mess with us.
					405//method not allowed
					);
			}
		}

			private function rest_input($new, $post) {
				$query = Request::all();
				if($new) {
					//Creates a new post
					$post = $this->post->instance();
					//alias can't be changed ever after initial submit.
					$alias = str_replace(' ', '-', $query['title']);
					$post->alias = preg_replace('/[^A-Za-z0-9\-]/', '', $alias).'-'.str_random(5).'-'.date('m-d-Y');//makes alias.  Maybe it should exclude other bits too...
				}
				$post->user_id = Auth::user()->id;
				
				//Gotta make sure to make the alias only alunum.  Don't change alias on the update.  We don't want to have to track this change.
				$post->story_type = isset($query['story_type']) ? $query['story_type'] : '';
				
				$post->category = isset($query['category']) ? serialize ( $query['category'] ) : '';
				$post->image = isset($query['image']) ? $query['image'] : 0;//If 0, then it means no photo.
				
				
				$post->title = $query['title'];
				$post->tagline_1 = isset($query['tagline_1']) ? $query['tagline_1'] : '';
				$post->tagline_2 = isset($query['tagline_2']) ? $query['tagline_2'] : '';
				$post->tagline_3 = isset($query['tagline_3']) ? $query['tagline_3'] : '';
				
				$post->body = isset($query['body']) ? trim( strip_tags($query['body'], '<p><i><b>') ) : '';//Body is the only updatable thing in an update scenario.

				//if the post is becoming published.
				if( $query['published'] ) {
					$post->published_at = DB::raw('NOW()');
				}
				$post->published = $query['published'];
				$post->draft = $query['draft'];//default is 1 so that it won't accidentally get published.
				return $post;
			}

		private function bot_detected() {
			if (isset($_SERVER['HTTP_USER_AGENT']) && preg_match('/bot|crawl|slurp|spider/i', $_SERVER['HTTP_USER_AGENT'])) {
				return true;
			} else {
				return false;
			}
		}
		
}