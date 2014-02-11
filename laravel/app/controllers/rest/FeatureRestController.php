<?php

//This isn't for the Admin Feature.  This is for the user feature.

class FeatureRestController extends \BaseController {
	
	public function index() {}
	
	public function show($id)
	{	
		$owns = Post::where('user_id', Auth::user()->id)
					->where('id', $id)
					->count();
		
		if($owns) {
			User::where('id', Auth::user()->id)
				->update(array('featured'=> $id));
				
			Session::put('featured', $id);
						
			return Response::json(
					array('result'=>'success'),
					200//response is OK!
				);
		} else {
			return Response::json(
					array('result'=>'fail'),
					200//response is OK!
				);
		}
	}
}