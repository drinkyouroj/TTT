<?php

class PostController extends \BaseController {

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
		//Returns all user posts.
		//$posts = Post::where('user_id', Auth::user()->id)->get();
		/*
		$posts = DB::table('posts')
				->join('comments', 'posts.id', '=', 'comments.user_id')
				->select('posts.*','comments.id')
				->get();
		*/
		
		$posts = Post::with('comments')->get();
		
		//We'll need to fix the below situation soon.
		/*
		$post_ids = array();
		foreach($posts as $post) {
			array_push($post_ids, $post['id']);
		}
		
		//grab any of the comments which are also included.
		//$comments = Comment::whereIn('post_id', $post_ids)->get();
		$comments = $posts = Post::all()->comments;
		$comments = $comments->toArray();
		
		$posts = $posts->toArray();
		foreach($posts as $key => $post) {
			$post['comments'] = array();
			foreach($comments as $comment) {
				if($post['id'] == $comment['post_id']) {
					array_push($post['comments'], $comment['id']);
				}
			}
			$posts[$key]['comments'] = $post['comments']; 
		}
		*/
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
	 * Show the form for creating a new resource.  This is based on GET action
	 *
	 * @return Response
	 */
	public function create()
	{
		//
		
	}

	/**
	 * Store a newly created resource in storage.  This is based on POST action
	 *
	 * @return Response
	 */
	public function store()
	{
		//Creates a new post
		$post = new Post;
		$post->user_id = Auth::user()->id;
		$post->title = Request::get('title');
		$post->alias = str_replace(' ', '-',Request::get('title'));//makes alias.  Maybe it should include other bits too...
		$post->tagline = Request::get('tagline');
		$post->category = Request::get('category');
		$post->image = Request::get('image');
		$post->body = Request::get('body');
		
		//Needs filtering and validation.
		$validator = Validator::make(
			array('')
		);
		
		$post->save();
		
		//send an ok response
		return Response::json(
			'Post Stored',
			200//response is OK!
		);
		
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
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
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
		
			if( Request::get('title')) {
				$post->title = Request::get('title');
				$post->alias = str_replace(' ', '-',Request::get('title'));//makes alias.  Maybe it should include other bits too...
			}
			
			if( Request::get('tagline')) {
				$post->tagline = Request::get('tagline');
			}
			
			if( Request::get('category')) {
				$post->category = Request::get('category');
			}
			
			if( Request::get('image')) {
				$post->image = Request::get('image');
			}
			
			if( Request::get('body')) {
				$post->body = Request::get('body');
			}
		}
		
		$post->save;
		
		return Response::json(
			'Post Updated'
			,
			200//response is OK!
		);
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

}