<?php

//PostRepository is just an interface!  Change it in the service provider if we ever need to.
//use AppStorage\Post\PostRepository;

class PostController extends BaseController {

	protected $softDelete = true;

	public function __construct(
							PostRepository $post,
							RepostRepository $repost,
							FavoriteRepository $favorite,
							LikeRepository $like,
							FollowRepository $follow,
							PostViewRepository $postview,
							ProfilePostRepository $profilepost,
							ActivityRepository $activity,
							FeedRepository $feed

							) {
		$this->post = $post;
		$this->repost = $repost;
		$this->favorite = $favorite;
		$this->like = $like;
		$this->follow = $follow;
		$this->postview = $postview;
		$this->profilepost = $profilepost;
		$this->activity = $activity;
		$this->feed = $feed;
	}

	public function getIndex() {
		$post = $this->post->random();
		return self::getPost($post->alias);
	}
	
    /**
     * Get Post
     */
    public function getPost($alias, $version = false)
    {
        $post = $this->post->findByAlias($alias);
		
		if($post != false) {//Post exists.
						
			if(!isset($post->user->username)) {
				$user_id = 1;//1 is the loneliest number! (aka user nobody)
				$post->user = User::find(1);
			} else {
				$user_id = $post->user->id;
			}
			
			//Logged in.
			if(Auth::check()) {
				
				$my_id = Auth::user()->id;//My user ID
				
				$is_following = $this->follow->is_following($my_id,$user_id);
								
				$is_follower = $this->follow->is_follower($my_id,$user_id);
									
				$liked = $this->like->has_liked($my_id, $post->id);
				
				$favorited = $this->favorite->has_favorited($my_id, $post->id);

				$reposted = $this->repost->has_reposted($my_id, $post->id);

			} else {
				//DEFAULTS for not logged in users
				$my_id = false;
				$is_follower = false;
				$is_following = false;
				$liked = false;
				$favorited = false;
				$reposted = false;
			}
			
			//Add the fact that the post has been viewed if you're not the owner and you're logged in.
			if($user_id != $my_id && Auth::check()) {
				$exists = $this->postview->exists($my_id, $post->id);

				//If the record doesn't exist, increment on the post view count and also add to the "viewed" in PostView
				if(!$exists) {
					$view = array(
						'user_id' => $my_id,
						'post_id' => $post->id
						);
					$this->postview->create($view);
					
					$this->post->incrementView($post->id);//increment on this post.
				}
			}
			
			// TODO: remove this once v2 post page is finished
			$template = 'generic.post';
			if ( $version ) {
				$template = 'v2/posts/post';
			}

	        return View::make( $template )
						->with('post', $post)
						->with('is_following', $is_following)//you are following this profile
						->with('is_follower', $is_follower)//This profile follows you.
						->with('bodyarray', PostLogic::divide_text($post->body, 1500))//This divides the body text into parts so that we can display them in multiple steps.
						->with('liked', $liked)
						->with('favorited', $favorited)
						->with('reposted', $reposted)
						;
		} else {
			return Redirect::to('/');
		}
    }
	

	/**
	 * Post form
	 */
	public function getPostForm($id=false) {
		if($id) {
			$post = $this->post->findById($id);
			return View::make('v2/posts/post_form')//Edit form only has to account for the text.  Not the entire listing.
					->with('post', $post)
					->with('edit', true);
		} else {
			//Gotta put in a query here to see if the user submitted something in the last 10 minutes
			$post = $this->post->lastPostUserId(Auth::user()->id);
			
			if(isset($post->id)) {
				//not an admin and 10min has not passed since your last post.
				if(!Session::get('admin') && strtotime(date('Y-m-d H:i:s', strtotime('-10 minutes'))) <= strtotime($post->created_at)  ){
					//Gotta make a new view for that.
					return View::make('generic/post-error');
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
	 * The REST version
	 * Sends back a JSON Response instead of a Redirect.
	*/
	public function postSavePost() {
		return self::savePost(true);
	}

		//Below assumes that the validation has passed.  It can be used to update or to save the post.
		private function savePost($rest=false) {
			$request = Request::all();
			$check_post = false;

			if( isset($request['id']) ) {
				$check_post = $this->post->findById( $request['id'] );
			}

			//The post exists.
			if(!empty($check_post->id) ) {
				//THIS is the update scenario.
				//let's double check that this ID exists and belongs to this user.			
				$new = false;
				
				if($check_post->published) {
					//Now that we know this exists, let's check to see if its been more than 3 days since it was initially posted.
					if(!Session::get('admin') &&
					   strtotime(date('Y-m-d H:i:s', strtotime('-72 hours'))) >= strtotime($check_post->published_at)) {
						//more than 72 hours has passed since the post was created.
						//TODO Maybe I should have this go somewhere more descriptive??
						if($rest) {
							return Response::json(
									array('error' => '72'),
									405//method not allowed
								);
						} else {
							return Redirect::to('profile');
						}
					}
				}
					if($rest) {
						$post = self::rest_input($new, $check_post);
					} else {
						$post = $this->post->input($new, $check_post);//Post object filter gets the input and puts it into the post.
					}
					
					$validator = $post->validate($post->toArray(),$check_post->id);//validation takes arrays.  Also if this is an update, it needs an id.


			} else {
				//New Post.
				$new = true;
				//Checking to see if this is an new post being applied by a punk
				$last_post = $this->post->lastPostUserId(Auth::user()->id);
				
				if( isset($last_post->id) && 
					$new && 
					!Session::get('admin') &&
					strtotime(date('Y-m-d H:i:s', strtotime('-10 minutes'))) <= strtotime($last_post->created_at))
				{
					//Nice try punk.  Maybe I should have this go somewhere more descriptive.
					if($rest) {
						return Response::json(
								array('error' => '10'),
								405//method not allowed
							);
					} else {
						return Redirect::to('profile');
					}
				}

				if($rest) {

					$post = self::rest_input($new, false);
				} else {
					$post = $this->post->input($new);//Post object creates objects.
				}
				
				$validator = $post->validate($post->toArray(),false);//no
			}
			
			
			if($validator->passes()) {//Successful Validation
				
				if($new) {
					$post->save();
					
					$user = Auth::user();
								
					//no featured for this user? set this new post as featured.
					if($user->featured == false) {
						User::where('id', Auth::user()->id)
							->update(array('featured' => $post->id));
					}
				
					//Put it into the profile post table (my posts or what other people see as your activity)
					$new_profilepost = array(
						'profile_id' => $user->id,
						'user_id' => $user->id,
						'post_id' => $post->id,
						'post_type' => 'post'
						);
					$this->profilepost->create($new_profilepost);
					
					//Also save this data to your own activity
					$new_activity = array(
						'user_id' => $user->id,//who's profile is this going to?
						'action_id' => $user->id,//Who's doing the action?
						'post_id' => $post->id,
						'post_type' => 'post'//new post!
						);
					$this->activity->create($new_activity);

					//If this is the web server upload this content to the CDN.
					if(App::environment() == 'web') {
						$file = OpenCloud::upload('Images', public_path().'/uploads/final_images/'.$post->image, $post->image);
					}
					
					//QUEUE
					//Add to follower's notifications.
					Queue::push('UserAction@newpost', 
								array(
									'post_id' => $post->id,
									'user_id' => $user->id,
									'username' => $user->username
									)
								);
				} else {
					//Gotta put in a thing here to get rid of all relations
					$post->categories()->detach();//Pivot! Pivot! 

					$post->update();
				}

				//Gotta save the categories pivot
				foreach(Input::get('category') as $k => $category) {
					if($k <= 1) {//This will ensure that no more than 2 are added at a time.
						$post->categories()->attach($category);
					} else {
						break;//let's not waste processes
					}
				}
					
				SolariumHelper::updatePost($post);//Let's add the data to solarium (Apache Solr)
				
				if($rest) {

					if($new) {
						$json_result = 'create';
					} else {
						$json_result = 'update';
					}

					return Response::json(
							array(
								'result' => $json_result,
								'id'	=> $post->id,
								'alias' => $post->alias
								),
							200 //happy happy joy joy!
						);
				} else {
					return Redirect::to('profile');
				}
				
			} else {//Failed Validation on serverside should just redirect no matter what.
				if($rest) {
					return Response::json(
						array('error' => '405'),//make sure that they are redirected. They might be trying to mess with us.
						405//method not allowed
						);
				} else {
					if($new) {
						return Redirect::to('profile/newpost')
									->withErrors($validator)
									->withInput();
					} else {
						return Redirect::to('profile/editpost/'.Input::get('id'))
									->withErrors($validator)
									->withInput();
					}
				}
				
			}
		}

			private function rest_input($new, $post) {
				$query = Request::all();
				if($new) {
					//Creates a new post
					$post = $this->post->instance();
					//alias can't be changed ever after initial submit.
					$post->alias = preg_replace('/[^A-Za-z0-9]/', '', $query['title'] ).'-'.str_random(5).'-'.date('m-d-Y');//makes alias.  Maybe it should exclude other bits too...
				}
				$post->user_id = Auth::user()->id;
				
				//Gotta make sure to make the alias only alunum.  Don't change alias on the update.  We don't want to have to track this change.
				$post->story_type = $query['story_type'];
				
				$post->category = serialize ($query['category'] );
				$post->image = $query['image'];//If 0, then it means no photo.
				
				
				$post->title = $query['title'];
				$post->tagline_1 = $query['tagline_1'];
				$post->tagline_2 = $query['tagline_2'];
				$post->tagline_3 = $query['tagline_3'];
				
				$post->body = $query['body'];//Body is the only updatable thing in an update scenario.
				$post->published = $query['published'];
				if($post->published) {
					$post->published_at = Carbon::createFromTimeStamp(strtotime(date('Y-m-d H:i:s'))) ;
				}
				$post->draft = $query['draft'];//default is 1 so that it won't accidentally get published.
				return $post;
			}


		
}