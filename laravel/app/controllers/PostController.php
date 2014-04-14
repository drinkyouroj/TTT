<?php
class PostController extends BaseController {

	protected $softDelete = true;

	public function getIndex() {
		//maybe some function that takes you to a random post here?
	}

    /**
     * Get Post
     */
    public function getPost($alias)
    {	
        $post = Post::where('alias', $alias);
		
		if($post->count()) {
			$post = $post->first();
			//Do the post math here
			$body_array = self::divide_text($post->body, 1500);//the length is currently set to 3000 chars
			
			if(!isset($post->user->username)) {
				$user_id = 1;//nobody
				$post->user = User::find(1);
			} else {
				$user_id = $post->user->id;
			}
			
			//DEFAULTS for not logged in
			$my_id = false;
			$is_follower = false;
			$is_following = false;
			$liked = false;
			$favorited = false;
			$reposted = false;
			
			//Logged in.
			if(Auth::check()) {
				$my_id = Auth::user()->id;//My user ID
				
				//TODO replace with the Follow helpers?
				$is_following = Follow::where('follower_id', '=', $my_id)
									->where('user_id', '=', $user_id)
									->count();
									
				$is_follower = Follow::where('follower_id', '=', $user_id)
									->where('user_id', '=', $my_id)
									->count();
									
				$liked = Like::where('user_id', $my_id)
							->where('post_id', $post->id)
							->count();
						
				$favorited = Favorite::where('user_id', $my_id)
							->where('post_id', $post->id)
							->count(); 
				$reposted = Repost::where('user_id', $my_id)
							->where('post_id', $post->id)
							->count();
						  
			}
			
			//Add the fact that the post has been viewed if you're not the owner and you're logged in.
			if($user_id != $my_id && Auth::check()) {
				$postview = PostView::where('user_id', $my_id)
						->where('post_id', $post->id)
						->count();
				//If the record doesn't exist, increment on the post view count and also add to the "viewed" in PostView
				if(!$postview) {
					$pv = new PostView;
					$pv->user_id = $my_id;
					$pv->post_id = $post->id;
					$pv->save();
					Post::where('alias', $alias)->increment('views', 1);//increment on this post.
				}
			}
			
	        return View::make('generic.post')
						->with('post', $post)
						->with('is_following', $is_following)//you are following this profile
						->with('is_follower', $is_follower)//This profile follows you.
						->with('bodyarray', $body_array)
						->with('liked', $liked)
						->with('favorited', $favorited)
						->with('reposted', $reposted)
						;
		} else {
			return Redirect::to('/');
		}
    }
	
		/**
		 * Divide the body text for display
		 * @param int $longString The body text
		 * @param int $maxLineLength The max length of each portion
		 * @return array $arrayOutput The output is the divided text. 
		 */
		private function divide_text($longString, $maxLineLength)
		{		
			$arrayWords = explode(' ', $longString);
			
			// Auxiliar counters, foreach will use them
			$currentLength = 0;
			$index = 0;
			$arrayOutput = array();
			$arrayOutput[0]= '';
			foreach($arrayWords as $k => $word)
			{
			    // +1 because the word will receive back the space in the end that it loses in explode()
				$wordLength = strlen($word) + 1;
			
				if( ( $currentLength + $wordLength ) <= $maxLineLength ) {
					
				    $arrayOutput[$index] .= $word . ' ';
		        	$currentLength += $wordLength;
					
			    } else {
			    	
			        $index += 1;
					$c = false;
					
			        $currentLength = $wordLength;
					
					//Below is to counter this weird thing where it gets rid of a space.
					if($c) {
						$arrayOutput[$index] = $word;
					} else {
						$arrayOutput[$index] = $word.' ';
					}
					$c++;
			    }
			}
			return $arrayOutput;
		}


	/**
	 * Post form
	 */
	public function getPostForm($id=false) {
		if($id) {
			$post = Post::where('id', '=', $id)->first();
			return View::make('posts/edit_form')//Edit form only has to account for the text.  Not the entire listing.
					->with('post', $post)
					->with('fullscreen', true);
		} else {
			//Gotta put in a query here to see if the user submitted something in the last 10 minutes 
			$post = Post::where('user_id','=', Auth::user()->id)
					->orderBy('created_at', 'DESC')//latest first
					->first();
			
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
			$check_post = Post::where('id', Input::get('id'))->first();
			
			if($check_post->id){
				$new = false;
				
				//Now that we know this exists, let's check to see if its been more than 3 days since it was initially posted.
				if(!Session::get('admin') &&
				strtotime(date('Y-m-d H:i:s', strtotime('-72 hours'))) >= strtotime($check_post->created_at)) {
					//more than 72 hours has passed since the post was created.
					//TODO Maybe I should have this go somewhere more descriptive??
					return Redirect::to('profile');
				}
				
				$post = self::post_object_input_filter($new,$check_post);//Post object filter gets the input and puts it into the post.
				$validator = $post->validate($post->toArray(),$check_post->id);//validation takes arrays.  Also if this is an update, it needs an id.
			}
			
		} else {
			//New Post.
			$new = true;
			//Checking to see if this is an new post being applied by a punk
			$last_post = Post::where('user_id','=', Auth::user()->id)
						->orderBy('created_at', 'DESC')//latest first
						->first();
			
			if( isset($last_post->id) && 
				$new && 
				!Session::get('admin') &&
				strtotime(date('Y-m-d H:i:s', strtotime('-10 minutes'))) <= strtotime($last_post->created_at))
			{
				//Nice try punk.  Maybe I should have this go somewhere more descriptive.
				return Redirect::to('profile');
			}
			$post = self::post_object_input_filter($new);//Post object creates objects.
			$validator = $post->validate($post->toArray(),false);//no
		}
		
		
		
		if($validator->passes()) {//Successful Validation
			if($new) {
				$post->save();
				
				$user = User::where('id', Auth::user()->id)->first();
							
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
				$profile_post->profile_id = Auth::user()->id;//post on your wall
				$profile_post->user_id = Auth::user()->id;//post by me
				$profile_post->post_id = $post->id;
				$profile_post->post_type = 'post';
				$profile_post->save();
				
				//Also save this data to your own activity
				$myactivity = new Activity;
				$myactivity->user_id = Auth::user()->id;//who's profile is this going to?
				$myactivity->action_id = Auth::user()->id;//Who's doing the action?
				$myactivity->post_id = $post->id;
				$myactivity->post_type = 'post';//new post!
				$myactivity->save();
				
				//QUEUE
				//Add to follower's notifications.
				Queue::push('UserAction@newpost', 
							array(
								'post_id' => $post->id,
								'user_id' => Auth::user()->id,
								'username' => Auth::user()->username,
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


		/**
		 * Input filtering.
		 */
		private function post_object_input_filter($new = false,$post = false)
		{	
			if($new) {
				//Creates a new post
				$post = new Post;
				$post->user_id = Auth::user()->id;
				
				//Gotta make sure to make the alias only alunum.  Don't change alias on the update.  We don't want to have to track this change.
				$post->alias = preg_replace('/[^A-Za-z0-9]/', '', Request::get('title')).'-'.str_random(5).'-'.date('m-d-Y');//makes alias.  Maybe it should exclude other bits too...
				$post->story_type = Request::get('story_type');
				
				$post->category = serialize(Request::get('category'));
				$post->image = Request::get('image','0');//If 0, then it means no photo.
			}
			
			$post->title = Request::get('title');
			$post->tagline_1 = Request::get('tagline_1');
			$post->tagline_2 = Request::get('tagline_2');
			$post->tagline_3 = Request::get('tagline_3');
			
			$post->body = Request::get('body');//Body is the only updatable thing in an update scenario.
			$post->published = 1;
			
			return $post;
		}
}