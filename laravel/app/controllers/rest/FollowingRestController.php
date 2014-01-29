<?php
class FollowingRestController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index() {} 
	 
	public function show()
	{
		$following_id = Follow::where('follower_id', '=', Request::segment(3))
					->select('user_id')
					->get();
		//Laravel, you fucking suck.  Your stupid 'with' doesn't work.
		$ids = array();
		
		foreach($following_id as $k => $follower) {
			$ids[$k] = $follower->user_id;
		}
		
		//If there are no people you're following, then we don't grab the SQL.
		if(count($ids)) {
			$following = User::whereIn('id', $ids)->select('id','username')->get()->toArray();
		} else {
			$following = array();
		}
		
		return Response::json(
			array('following'=>$following),
			200//response is OK!
		);
	}

}