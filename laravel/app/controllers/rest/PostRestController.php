<?php
class PostRestController extends \BaseController {

	public function __construct(
							PostRepository $post,
							ProfilePostRepository $profilepost
							) {
		$this->beforeFilter('auth');//This is probably not required as its filtered at another stage.
		$this->post = $post;
		$this->profilepost = $profilepost;
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
		$post = $this->post->findById($id, true, array('user'));
		
		if(is_object($post)) {
			//Sends back a response of the post.
			return Response::json(
				array(
					$post->toArray()
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
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		/*  Currently not used.
		//Needs to be limited to the user since this is an update scenario
		$post = $this->post->findById($id);
		
		if($post->user_id == Auth::user()->id) {
		
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
		} else {
			return Response::json(
				"Not your post",
				200//response is OK!
			);
		}
		*/
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{	
		$user_id = Auth::user()->id;
		$owns = $this->post->owns($id, $user_id);
		
		if($owns) {
			
			//Grab the post for rest of this.
			$post = $this->post->findById($id);
			
			//Delete Scenario
			if($post->published) {

				//if the post was featured set it back to nothing.
				if($post->featured) {
					User::where('id', $user_id)->update(array('featured'=>0));
				}
				
				//unpublish the post.
				$this->post->unpublish($id);
				
				//Take it out of the activities. (maybe queue this too?)
				Activity::where('post_id', $id)
						->where('user_id', $user_id)//This is based on who is affected.
						->delete();
				
				//Gotta get rid of it from the MyPosts/External Profile View 
				$this->profilepost->delete($user_id,$post, 'post');
				
				return Response::json(
						array('result' =>'unpublished'),
						200//response is OK!
					);
			} else {
				//UnDelete Scenario
				$this->post->publish($id);

				$this->profilepost->publish($user_id, $post->id);
		
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
			$post = $this->post->instance();
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
