<?php

class PostController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//Returns all user posts.
		$posts = Post::where('user_id', Auth::user()->id)->get();
		
		return Response::json(
			array(
				'error' => false,
				'posts' => $posts->toArray()
			),
			200//response is OK!
		);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
		
	}

	/**
	 * Store a newly created resource in storage.
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
			array(
				'error' => false,
				'message' => 'Post Stored'
			),
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
		//Gets one 
		$post = Post::where('id', $id)->take(1)->get();
		
		//Sends back a response of the post.
		return Response::json(
			array(
				'error' => false,
				'post' => $post->toArray()
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
		
		//We'll have ways to stop this, but in case someone really wants to update something that's been published for more than a day.
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
			array(
				'error' => false,
				'message' => 'Post Updated'
			),
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
		//
		
	}

}