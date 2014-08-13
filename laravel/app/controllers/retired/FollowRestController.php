<?php
class FollowRestController extends \BaseController {
	
	//not the best usage (using get), but this works
	public function show()
	{
		//request sometimes comes in as a value
		$other_user_id = intval(Request::segment(3));
		$my_user_id = Auth::user()->id;
		$my_username = Auth::user()->username;
		
		if($other_user_id && !empty($other_user_id)) {
		
			$exists = Follow::where('user_id', $other_user_id)
							->where('follower_id', $my_user_id)
							->count();
			
			if($exists) {//Relationship already exists
				
				Follow::where('user_id', $other_user_id)
						->where('follower_id', $my_user_id)
						->delete();
				
				NotificationLogic::unfollow($other_user_id);
				
				return Response::json(
					array('result'=>'deleted'),
					200//response is OK!
				);
			} else {//Doesn't exists
				//Crete a new follow
				$follow = new Follow;
				$follow->user_id = $other_user_id;
				$follow->follower_id = $my_user_id;//Gotta be from you.
				$follow->save();
				
				NotificationLogic::follow($other_user_id);
				
				return Response::json(
					array('result'=>'success'),
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