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
		
	}

	
	/**
	 * Store a newly created resource in storage.  This is based on POST action
	 *
	 * @return Response
	 */
	public function store()
	{
				
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
		$post = Post::where('id', $id)
					->where('published',1)->with('user')->first();
		if(is_object($post)) {
			/*
			$user = User::where('id', $post->user_id)
						->select('username')
						->first();
			*/
			//Sends back a response of the post.
			return Response::json(
				array(
					$post->toArray(),
					//'user' => $user->toArray()
				),
				200//response is OK!
			);
		} else {
			return Response::json(
				array(
					'post' => array()
				),
				200//response is OK!
			);
		}
		
		/*
		//grab any of the comments which are also included.
		$comments = Comment::where('post_id', $id)->get();
		$comments = $comments->toArray();
		
		$comments_id = array();
		foreach($comments as $com) {
			array_push($comments_id, $com['id']);
		}
		
		$post[0]['comments'] = $comments_id;
		*/
		
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
		//
		$owns = Post::where('user_id', Auth::user()->id)
					->where('id', $id)
					->count();
		
		if($owns) {
			
			$post = Post::where('id', $id)->first();
			
			//Delete Scenario
			if($post->published) {
				//if the post was featured set it back to nothing.
				if($post->featured) {
					User::where('id', Auth::user()->id)->update(array('featured'=>0));
				}
				
				Post::where('id', $id)
					->update(array('published'=>0));
				
				//Take it out of the activities. (maybe queue this too?)
				Activity::where('post_id', $id)
						->where('user_id', Auth::user()->id)//This is based on who is affected.
						->delete();
				
				//Gotta get rid of it from the MyPosts/External Profile View 
				ProfilePost::where('post_id', $id)
						->where('profile_id', Auth::user()->id)//This is based on who is affected.
						->delete();
				
				return Response::json(
						array('result' =>'unpublished'),
						200//response is OK!
					);
			} else {
				//UnDelete Scenario
				Post::where('id', $id)
					->update(array('published'=>1));
				
				ProfilePost::onlyTrashed()
						->where('post_id', $id)
						->where('profile_id', Auth::user()->id)//This is based on who is affected.
						->restore();
						
				return Response::json(
						array('result' =>'republished'),
						200//response is OK!
					);
			}
		} else {
			return Response::json(
					array('result' =>'fail'),
					200//response is OK!
				);
		}
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
