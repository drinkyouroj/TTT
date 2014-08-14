<?php
class PostRestController extends \BaseController {

	public function __construct(
							PostRepository $post,
							ProfilePostRepository $profilepost,
							ActivityRepository $activity
							) {
		$this->beforeFilter('auth');//This is probably not required as its filtered at another stage.
		$this->post = $post;
		$this->profilepost = $profilepost;
		$this->activity = $activity;
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
				$this->activity->deleteAll($user_id, $id);
								
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
