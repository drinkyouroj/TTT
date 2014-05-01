<?php
class LikeRestController extends \BaseController {

	public function __construct(PostRepository $post) {
		$this->post = $post;
	}

	public function index()
	{
		return Response::json(
			array('result'=>'success'),
			200//response is OK!
		);
	}

	public function store()
	{
		
	}
	
	//not the best usage (using get), but this works
	public function show()
	{
		if(Request::segment(3) != 0) {
			$exists = Like::where('post_id', '=', Request::segment(3))
							->where('user_id', '=', Auth::user()->id)
							->count();
							
			$owns = $this->post->owns(Request::segment(3), Auth::user()->id);
			
			if(!$exists && !$owns) {//Doesn't exists
				//Crete a new Like
				$like = new Like;
				$like->post_id = Request::segment(3);
				$like->user_id = Auth::user()->id;//Gotta be from you.
				$like->save();
				
				$this->post->incrementLike(Request::segment(3));
				
				if($like->id) {
					return Response::json(
						array('result'=>'success'),
						200//response is OK!
					);
				}
			} elseif($exists) {//Relationship already exists
			
				Like::where('post_id', '=', Request::segment(3))
					->where('user_id', '=', Auth::user()->id)
					->delete();
				
				$this->post->decrementLike(Request::segment(3));
				
				return Response::json(
					array('result'=>'deleted'),
					200//response is OK!
				);
			}
		}
		return Response::json(
			array('result'=>'fail'),
			200//response is OK!
		);
	}
	
}