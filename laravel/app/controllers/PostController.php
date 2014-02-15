<?php
class PostController extends BaseController {

	public function getIndex() {
		//maybe some function that takes you to a random post here?
	}

    /**
     * Get Post
     */
    public function getPost($alias)
    {	
        $post = Post::where('alias', $alias)->first();
		
		//Do the post math here
		$body_array = self::divide_text($post->body, 1500);//the length is currently set to 3000 chars
		$user_id = $post->user->id;
		
		$is_following = Follow::where('follower_id', '=', Session::get('user_id'))
							->where('user_id', '=', $user_id)
							->count();
		$is_follower = Follow::where('follower_id', '=', $user_id)
							->where('user_id', '=', Session::get('user_id'))
							->count();
		
		//Add the fact that the post has been viewed if you're not the owner and you're logged in.
		if($user_id != Session::get('user_id') && Auth::check()) {
			$postview = PostView::where('user_id', Session::get('user_id'))
					->where('post_id', $post->id)
					->count();
			//If the record doesn't exist, increment on the post view count and also add to the "viewed" in PostView
			if(!$postview) {
				$pv = new PostView;
				$pv->user_id = Session::get('user_id');
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
					;
    }
	
		//Straight out of compton (or stackoverflow)
		private function divide_text($longString, $maxLineLength)
		{		
			$arrayWords = explode(' ', $longString);
			
			// Auxiliar counters, foreach will use them
			$currentLength = 0;
			$index = 0;
			$arrayOutput = array();
			$arrayOutput[0]= '';
			foreach($arrayWords as $word)
			{
			    // +1 because the word will receive back the space in the end that it loses in explode()
				$wordLength = strlen($word) + 1;
			
				if( ( $currentLength + $wordLength ) <= $maxLineLength ) {
					
				    $arrayOutput[$index] .= $word . ' ';
		        	$currentLength += $wordLength;
					
			    } else {
			    	
			        $index += 1;
			        $currentLength = $wordLength;
			        $arrayOutput[$index] = $word;
					
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
			$post = Post::where('user_id','=', Session::get('user_id'))
					->orderBy('created_at', 'DESC')//latest first
					->first();
			
			if(isset($post->id)) {
				//not an admin and 10min has not passed since your last post.
				if(!Session::get('admin') && strtotime(date('Y-m-d H:i:s', strtotime('-10 minutes'))) <= strtotime($post->created_at)  ){
					//Gotta make a new view for that.
					return View::make('generic/error')
						->with('message', "Can't be spammin around!");
				}
			}
					
			return View::make('posts/form')->with('fullscreen', true);
		}
		
	}
	
	/**
	 * Where the Posts are born! (and edited)
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
					//Nice try punk.  Maybe I should have this go somewhere more descriptive.
					return Redirect::to('profile');
				}
				
				$post = self::post_object_input_filter($new,$check_post);//Post object filter gets the input and puts it into the post.
			}
			
		} else {
			//New Post.
			$new = true;
			//Checking to see if this is an new post being applied by a punk
			$last_post = Post::where('user_id','=', Session::get('user_id'))
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
		}
		
		 
		$validator = $post->validate($post->toArray());//validation takes arrays
		
		if($validator->passes()) {//Successful Validation
			if($new) {
				$post->save();
				
				$user = User::where('id', Auth::user()->id)->first();
							
				//is this your first post?
				if(!$user->featured) {
					User::where('id', Auth::user()->id)
						->update(array('featured' => $post->id));
				}
				
				//Gotta put in a thing here to get rid of all relations if this is an update.
				$post->categories()->detach();//Pivot! Pivot! 
	
				//Gotta save the categories pivot
				foreach(Input::get('category') as $k => $category) {
					if($k <= 2) {//This will ensure that no more than 3 are added at a time.
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
								'user_id' => Auth::user()->id
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
				$post->alias = preg_replace('/[^A-Za-z0-9]/', '', Request::get('title')).'-'.date('m-d-Y');//makes alias.  Maybe it should exclude other bits too...
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