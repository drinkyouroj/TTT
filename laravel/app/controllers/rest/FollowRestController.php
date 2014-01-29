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
		
		if($exists) {//Relationship already exists
			
			Follow::where('user_id', '=', Request::segment(3))
					->where('follower_id', '=', Auth::user()->id)
					->delete();
		
			return Response::json(
				array('result'=>'deleted'),
				200//response is OK!
			);
		} else {//Doesn't exists
			//Crete a new follow
			$follow = new Follow;
			$follow->user_id = Request::segment(3);
			$follow->follower_id = Auth::user()->id;//Gotta be from you.
			$follow->save();
			
			return Response::json(
				array('result'=>'success'),
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