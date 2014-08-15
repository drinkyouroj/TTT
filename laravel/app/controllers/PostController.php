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
							PostViewRepository $postview

							) {
		$this->post = $post;
		$this->repost = $repost;
		$this->favorite = $favorite;
		$this->like = $like;
		$this->follow = $follow;
		$this->postview = $postview;
	}

	public function getIndex() {
		$post = $this->post->random();
		return self::getPost($post->alias);
	}
	
    /**
     * Get Post
     */
    public function getPost($alias)
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
			
	        return View::make('generic.post')
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
			return View::make('posts/edit_form')//Edit form only has to account for the text.  Not the entire listing.
					->with('post', $post)
					->with('fullscreen', true);
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
					
			return View::make('posts/new_form')->with('fullscreen', true);
		}
		
	}
	
	/**
	 * Save the Post Form
	 * 	Where the Posts are born! (and edited)
	 * 
	 */
	public function postPostForm() {
		//Detect if this is an update scenario or if its new and prepare the data accordingly.
		if(Input::get('id')) {
			//THIS is the update scenario.
			//let's double check that this ID exists and belongs to this user.
			$check_post = $this->post->findById(Input::get('id'));
			
			if($check_post){
				$new = false;
				
				//Now that we know this exists, let's check to see if its been more than 3 days since it was initially posted.
				if(!Session::get('admin') &&
				strtotime(date('Y-m-d H:i:s', strtotime('-72 hours'))) >= strtotime($check_post->created_at)) {
					//more than 72 hours has passed since the post was created.
					//TODO Maybe I should have this go somewhere more descriptive??
					return Redirect::to('profile');
				}
				
				$post = $this->post->input($new,$check_post);//Post object filter gets the input and puts it into the post.
				$validator = $post->validate($post->toArray(),$check_post->id);//validation takes arrays.  Also if this is an update, it needs an id.
			}
			
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
				return Redirect::to('profile');
			}
			$post = $this->post->input($new);//Post object creates objects.
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
				
				//Gotta put in a thing here to get rid of all relations if this is an update.
				$post->categories()->detach();//Pivot! Pivot! 
	
				//Gotta save the categories pivot
				foreach(Input::get('category') as $k => $category) {
					if($k <= 1) {//This will ensure that no more than 2 are added at a time.
						$post->categories()->attach($category);
					} else {
						break;//let's not waste processes
					}
				}
			
				//Put it into the profile post table (my posts or what other people see as your activity)
				$profile_post = new ProfilePost;
				$profile_post->profile_id = $user->id;//post on your wall
				$profile_post->user_id = $user->id;//post by me
				$profile_post->post_id = $post->id;
				$profile_post->post_type = 'post';
				$profile_post->save();
				
				//Also save this data to your own activity
				$myactivity = new Activity;
				$myactivity->user_id = $user->id;//who's profile is this going to?
				$myactivity->action_id = $user->id;//Who's doing the action?
				$myactivity->post_id = $post->id;
				$myactivity->post_type = 'post';//new post!
				$myactivity->save();
				
				//If this is the web server upload this content to the cdn.
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
				$post->update();
			}
				
			SolariumHelper::updatePost($post);//Let's add the data to solarium (Apache Solr)
			
			return Redirect::to('profile');
			
		} else {//Failed Validation
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