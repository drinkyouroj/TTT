<?php
class ProfileController extends BaseController {

	public function __construct() {

		View::composer('*', function($view) {
			$alias = Request::segment(2);
			
			//Unfortunately view composer currently sucks at things so this is a crappy work around.
			$not_segment = array(
						Session::get('username'),
						'newpost',
						'editpost',
						'newmessage',
						'submitpost'
						);
			
			if($alias && !in_array($alias, $not_segment) ) {//This is for other users. not yourself
				$user = User::where('username', '=', $alias)->first();
				$user_id = $user->id;//set the profile user id for rest of the session.
				
			} else {
				//We're doing the user info loading this way to keep the view clean.
				$user_id = Session::get('user_id');
			}
			
			$followers = Follow::where('user_id', '=', $user_id)->count();
			$following = Follow::where('follower_id', '=', $user_id)->count();
			
			$view->with('followers', $followers)
					->with('following', $following);
		});
	}

	/**
	 * Gets the profile (including your own)
	 */
	public function getProfile($alias=false) {
		
		$is_following = false;
		$is_follower = false;
		$mutual = false;
		$activity = false;
		
		if($alias && $alias != Session::get('username')) {//This is for other users. not yourself
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
			
		} else {
			//We're doing the user info loading this way to keep the view clean.
			$user_id = Session::get('user_id');
			$user = User::where('id', '=', $user_id)->first();
			$activity = ProfilePost::where('profile_id','=', $user_id)->get();//get the 
		}
		
		$posts = Post::where('user_id', '=', $user_id)->get();
		
		return View::make('profile/index')
				->with('posts', $posts)
				->with('activity', $activity)
				->with('user', $user)
				->with('is_following', $is_following)//you are following this profile
				->with('is_follower', $is_follower)//This profile follows you.
				->with('mutual', $mutual);
	}

	/**
	 * Post form
	 */
	public function getPostForm($id=false) {
		if($id) {
			$post = Post::where('id', '=', $id);
			return View::make('posts/form')
					->with('post', $post);
		} else {
			return View::make('posts/form');
		}
		
	}
	
	public function postPostForm() {

		$new = true;
		if(Input::get('id')) {
			$new = false;
		}
		
		$post = self::object_input_filter($new);//Post object takes objects.
		$validator = $post->validate($post->toArray());//validation takes arrays (this is so stupid Laravel!)
		
		if($validator->passes()) {//Successful Validation
			$post->save();

			//Gotta put in a thing here to get rid of all relations if this is an update.
			$post->categories()->detach();

			//Gotta save the categories pivot
			foreach(Input::get('category') as $k => $category) {
				if($k <= 2) {//This will ensure that no more than 3 are added at a time.
					$post->categories()->attach($category);
				} else {
					break;//let's not waste processes
				}
			}
		
			//Put it into the profile post table 
			$profile_post = new ProfilePost;
			$profile_post->profile_id = Auth::user()->id;//post on your wall
			$profile_post->user_id = Auth::user()->id;//post by me
			$profile_post->post_id = $post->id;
			$profile_post->post_type = 'post';
			$profile_post->save();
			
			
			
			//Send it out to your followers (maybe this function should be queued)
			$followers = Follow::where('user_id', Auth::user()->id);
			foreach($followers as $follower){
				$follower_post = new ProfilePost;
				$profile_post->user_id = $follower->user_id;//set it to show on this follower's wall.
				$profile_post->post_id = $post->id;//set the id of the post.
				$profile_post->post_type = 'post';//new post by poster.
				$profile_post->save();
				//Gotta add notifications.
			}
			
			return Redirect::to('profile');
					
		} else {//Failed Validation
			dd($validator->failed());
			if($new) {
				return Redirect::to('profile/newpost')->withErrors($validator);
			} else {
				return Redirect::to('profile/editpost/'.Input::get('id'))->withErrors($validator);
			}
			
		}
		
	}
	
	
	public function getPostMessage($id=false) {
		if($id) {
			//should probably put a drafts checker or something.
			$message = Message::where('id', '=', $id);
			return View::make('posts/form')
					->with('message', $message);
		} else {
			return View::make('posts/form');
		}
		
	}
	
	/**
	 * Laravel, you're a fucking retard when it comes to input filtering.
	 */
		private function object_input_filter($new = false)
		{
			//Creates a new post
			/*
			
			*/
			$post = new Post;
			$post->user_id = Auth::user()->id;
			$post->title = Request::get('title');
			if($new) {
				$post->alias = str_replace(' ', '-',Request::get('title'));//makes alias.  Maybe it should include other bits too...
			}
			$post->story_type = Request::get('story_type');
			
			$post->tagline_1 = Request::get('tagline_1');
			$post->tagline_2 = Request::get('tagline_2');
			$post->tagline_3 = Request::get('tagline_3');
			
			$post->category = serialize(Request::get('category'));
			$post->image = Request::get('image','1');
			$post->body = Request::get('body');
			
			return $post;
		}
	
		private function array_input_filter($new = false) {
			$post = array();
			$post['user_id'] = Auth::user()->id;
			$post['title'] = Request::get('title');
			if($new) {
				$post['alias'] = str_replace(' ', '-',Request::get('title'));//makes alias.  Maybe it should include other bits too...
			}
			$post['story_type'] = Request::get('story_type');
			
			$post['tagline_1'] = Request::get('tagline_1');
			$post['tagline_2'] = Request::get('tagline_2');
			$post['tagline_3'] = Request::get('tagline_3');
			
			$post['category'] = serialize(Request::get('category'));
			$post['image'] = Request::get('image','1');
			$post['body'] = Request::get('body');
			
			return $post;
		}
}