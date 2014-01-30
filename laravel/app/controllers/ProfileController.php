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
						'replymessage',
						'submitpost',
						'comment',
						'commentform',
						'messages'
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
			$activity = ProfilePost::where('profile_id','=', $user_id)
						->orderBy('created_at', 'DESC')
						->get();//get the activities 
			
		}
		$likes = Like::where('user_id', '=', $user_id)->take(5)->get();
		$posts = Post::where('user_id', '=', $user_id)->get();
		
		return View::make('profile/index')
				->with('posts', $posts)
				->with('likes', $likes)
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
			//Gotta put in a query here to see if the user submitted something in the last 5 minutes 
			$post = Post::where('user_id','=', Session::get('user_id'))
					->orderBy('created_at', 'DESC')//latest first
					->first();
			
			if(isset($post->id)) {
				if(Session::get('username') != 'ryuhei'  && strtotime(date('Y-m-d H:i:s', strtotime('-5 minutes'))) <= strtotime($post->created_at)  ){
					return View::make('generic/error')
						->with('message', "Can't be spammin around!");
				}
			}
					
			return View::make('posts/form');
		}
		
	}
	
	public function postPostForm() {
		//Detect if this is an update scenario or if its new
		$new = true;
		if(Input::get('id')) {
			$new = false;
		}
		
		$post = self::post_object_input_filter($new);//Post object creates objects.
		$validator = $post->validate($post->toArray());//validation takes arrays
		
		if($validator->passes()) {//Successful Validation
		
			$post->save();
			
			SolariumHelper::updatePost($post);//Let's add the data to solarium

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
		private function post_object_input_filter($new = false)
		{
			//Creates a new post
			$post = new Post;
			$post->user_id = Auth::user()->id;
			$post->title = Request::get('title');
			if($new) {
				//Gotta make sure to make the alias only alunum
				$post->alias = preg_replace('/[^A-Za-z0-9]/', '', Request::get('title')).'-'.date('m-d-Y');//makes alias.  Maybe it should exclude other bits too...
			}
			$post->story_type = Request::get('story_type');
			
			$post->tagline_1 = Request::get('tagline_1');
			$post->tagline_2 = Request::get('tagline_2');
			$post->tagline_3 = Request::get('tagline_3');
			
			$post->category = serialize(Request::get('category'));
			$post->image = Request::get('image','0');//If 0, then it means no photo.
			$post->body = Request::get('body');
			
			return $post;
		}
/********************************************************************
 * Comments
*/

	public function postCommentForm()
	{
		$post_id = Request::segment(3);
		$comment = self::comment_object_input_filter();
		$validator = $comment->validate($comment->toArray());
		
		if($validator->passes()) {
			$comment->save();
			return Redirect::to('posts/'.$comment->post->alias.'#comment-'.$comment->id);
		} else {
			return Redirect::to('posts/'.$comment->post->alias)
							->withErrors($validator)
							->withInput();;
		} 
	}


		private function comment_object_input_filter()
		{
			$comment = new Comment;
			$comment->user_id = Auth::user()->id;
			$comment->post_id = Request::segment(3);
			if(Request::get('reply_id')) {
				$comment->parent_id = Request::get('reply_id');
			}
			$comment->body = Request::get('body');
			return $comment;
		}
		
		private function comment_body_filter($body)
		{
			
		}
		

	public function getCommentForm($post_id, $reply_id) {
		$post = Post::find($post_id);
		return View::make('generic/commentform')
				->with('post', $post)
				->with('reply_id', $reply_id);
	}

/********************************************************************
 * Messages
*/
	/**
	 * Messages inbox
	 */
	
	public function getMessageInbox() {
		$messages = Message::where('to_uid', Session::get('user_id'))
						->orderBy('id', 'DESC')
						->get();
		return View::make('messages/inbox')
					->with('messages', $messages);
	}
	
	/**
	 * Message form 
	 */
	public function getMessageForm($user_id=false, $reply_id = false) {
		if($reply_id) {
			//message you're replying to.
			$message = Message::where('id', '=', $reply_id)->first();
			$user = User::where('id', '=', $message->from_uid)->first();
			return View::make('messages/form')
					->with('message', $message)
					->with('message_user', $user);
		} else {
			$user = User::where('id', '=', $user_id)->first();
			//Can't find that user?
			if(is_null($user)) {
				//Gotta find all the mutual follows!
				$user = Follow::where('follower_id', '=', Session::get('user_id'))
								->where('follower_id', '=', $user_id)
								->where('user_id', '=', Session::get('user_id'))
								->where('user_id', '=', $user_id)
								->get();
				$user = $user->toArray();//pass on only the user info
				//This gets checked is_array on the otherside
			}
			return View::make('messages/form')
					->with('message_user', $user);
		}
	}
	
	public function getMessageReplyForm($reply_id=false) {
		if($reply_id) {
			return self::getMessageForm(false, $reply_id);
		} else {
			return Redirect::to('profile');
		}
	}
	
	
	public function postMessageForm() {
		
		$message = self::message_object_input_filter();//Post object takes objects.
		$validator = $message->validate($message->toArray());//validation takes arrays
		
		if($validator->passes()) {//Successful Validation
			//Gotta check to see if they are mutually following.
			
			$message->save();
			return Redirect::to('profile');
		} else {
			if($message->reply_id != 0 || !empty($message->reply_id)) {
				//this is a reply situation
				return Redirect::to('profile/replymessage/'.$message->reply_id)->withErrors($validator)->withInput();
			} else {
				//this is a new message situation
				return Redirect::to('profile/newmessage/')->withErrors($validator)->withInput();;
			}
		}
	}
	
	
		private function message_object_input_filter($new = false)
		{
			//Creates a new post
			$message = new Message;
			$message->from_uid = Auth::user()->id;
			$message->to_uid = Request::get('to_uid');
			$message->reply_id = Request::get('reply_id');//which message are we replying to?			
			$message->body = Request::get('body');
			
			return $message;
		}
		
		private function mutual_follow_check($uid1, $uid2) {
			
		}
	
}