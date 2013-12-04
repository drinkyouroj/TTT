<?php
class FollowRestController extends \BaseController {

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
		$exists = Follow::where('user_id', '=', Request::segment(3))
						->where('follower_id', '=', Auth::user()->id)
						->count();
		
		if(!count($exists)) {//Doesn't exists
			//Crete a new follow
			$follow = new Follow;
			$follow->user_id = Request::segment(3);
			$follow->follower_id = Auth::user()->id;//Gotta be from you.
			$follow->save();
			if($follow->id) {
				return Response::json(
					array('result'=>'success'),
					200//response is OK!
				);
			}
		} elseif(count($exists)) {//Relationship already exists
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