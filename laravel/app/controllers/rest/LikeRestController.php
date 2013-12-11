<?php
class LikeRestController extends \BaseController {

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
		$exists = Like::where('post_id', '=', Request::segment(3))
						->where('user_id', '=', Auth::user()->id)
						->count();
		
		if(!$exists) {//Doesn't exists
			//Crete a new follow
			$like = new Like;
			$like->post_id = Request::segment(3);
			$like->user_id = Auth::user()->id;//Gotta be from you.
			$like->save();
			if($like->id) {
				//Add to notifications
				
				
				
				return Response::json(
					array('result'=>'success'),
					200//response is OK!
				);
			}
		} elseif($exists) {//Relationship already exists
			return Response::json(
				array('result'=>'exists'),
				200//response is OK!
			);
		}
		
		return Response::json(
			array('result'=>'fail'),
			200//response is OK!
		);
	}

	public function missingMethod($parameters)
	{
	    return Response::json(
			array('result'=>'fail'),
			200//response is OK!
		);
	}

}