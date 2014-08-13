<?php
class FollowersRestController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
 	public function index() {}
	 
	public function show($id)
	{
		if($id) {
			$followers_id = Follow::where('user_id', '=', $id)
						->select('follower_id')
						->get();
			
			//Laravel, you fucking suck.  Your stupid 'with' doesn't work.
			$ids = array();
			
			foreach($followers_id as $k => $follower) {
				$ids[$k] = $follower->follower_id;
			}
			
			//If there are no followers, then we don't grab the SQL.
			if(count($ids)) {
				$followers = User::whereIn('id', $ids)->select('id','username')->get()->toArray();
			} else {
				$followers = array();
			}
			
			return Response::json(
				array('followers'=> $followers),
				200//response is OK!
			);
		} else {
			return Response::json(
				array('followers'=>'fail'),
				200//response is OK!
			);
		}
	}

}