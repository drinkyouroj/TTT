<?php
class PostRestController extends \BaseController {

	public function __construct() {
		$this->beforeFilter('auth');
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$posts = Post::with('comments')->get();
		$posts = $posts->toArray();
		
		//Below is something ember requires its kind of not efficient at this moment, but we'll deal with it for now.
		foreach($posts as $key => $post) {
			$post_comments = array();
			foreach($post['comments'] as $comment) {
				array_push($post_comments,$comment['id']);
			}
			$posts[$key]['comments'] = $post_comments;
		}
		
		return Response::json(
			array('posts' => $posts),
			200//response is OK!
		);
	}

	
	/**
	 * Store a newly created resource in storage.  This is based on POST action
	 *
	 * @return Response
	 */
	public function store()
	{
		//Let's get that filtering done with in one place.
		$post =　self::input_filter(true);//true = new
		
		//Needs filtering and validation.
		$validator = Post::validate($post);
		
		if($validtor->passes()) {
			$post->save();
			//Put it into the profile post table 
			$profile_post = new ProfilePost;
			$profile_post->user_id = Auth::user()->id;//post as new to yourself
			$profile_post->post_id = $post->id;
			$profile_post->post_type = 'post';
			$profile_post->save();
			
			//Send it out to your followers
			$followers = Follow::where('user_id', Auth::user()->id);
			foreach($followers as $follower){
				$follower_post = new ProfilePost;
				$profile_post->user_id = $follower->user_id;//set it to show on this follower's wall.
				$profile_post->post_id = $post->id;//set the id of the post.
				$profile_post->post_type = 'post';//new post by poster.
				$profile_post->save();
			}
			
			//send an ok response
			return Response::json(
				'Post Stored',
				200//response is OK!
			);			
		} else {
			//send an ok response
			return Response::json(
				'Not Good',
				500//response is OK!
			);
		}
		
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//Gets one with the right id.
		$post = Post::where('id', $id)->take(1)->get();
		$post = $post->toArray();
		
		//grab any of the comments which are also included.
		$comments = Comment::where('post_id', $id)->get();
		$comments = $comments->toArray();
		
		$comments_id = array();
		foreach($comments as $com) {
			array_push($comments_id, $com['id']);
		}
		
		$post[0]['comments'] = $comments_id;
		
		//Sends back a response of the post.
		return Response::json(
			array(
				'post' => $post[0],//this is to pass back an object instead of an array.
				'comments' => $comments
			),
			200//response is OK!
		);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//Needs to be limited to the user since this is an update scenario
		$post = Post::where('user_id', Auth::user()->id)->find($id);
		
		//We'll have ways to stop this on the front end, but in case someone really wants to update something that's been published for more than a day.
		if((time()-(60*60*24))< strtotime($post->created_at)) {
			$post = self::input_filter(false);//Not a new entry.  Don't want the alias changing
			$validate = Post::validate($post);
			
			if($validate->passes()) 
			{
				$post->save();
			}
			
			return Response::json(
				'Post Updated',
				200//response is OK!
			);
		} else {
			return Response::json(
				"Can't update this one.  Too late!",
				200//response is OK!
			);
		}
		
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//This should be reserved for users with admin roles.
		
	}
	
		//Grabs the inputs and places them in the right place within the object.
		private function input_filter($new = false)
		{
			//Creates a new post
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
			
			$post->category = Request::get('category');
			$post->image = Request::get('image');
			$post->body = Request::get('body');
			
			return $post;
		}

}
