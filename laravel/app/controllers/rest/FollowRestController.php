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
		if(Request::segment(3) != 0) {
		
			$exists = Follow::where('user_id', '=', Request::segment(3))
							->where('follower_id', '=', Auth::user()->id)
							->count();
			
			if($exists) {//Relationship already exists
				
				Follow::where('user_id', '=', Request::segment(3))
						->where('follower_id', '=', Auth::user()->id)
						->delete();
						
				//Gotta delete the notifications so that it doesn't multiply.
				Notification::where('user_id', Request::segment(3))
							->where('action_id', Auth::user()->id)
							->where('notification_type', 'follow')
							->where('post_id', 0)
							->delete();
				
				Motification::where('user_id', intval(Request::segment(3)))
							->where('user', Auth::user()->username)
							->where('notification_type', 'follow')
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
				
				//TODO get rid of the original notifcation code soon.
				$notification = new Notification;
				$notification->post_id = 0;//zero means that this is a system level notification
				$notification->user_id = Request::segment(3);
				$notification->action_id = Auth::user()->id;
				$notification->notification_type = 'follow';
				$notification->save();
				
				//Below is an insert function.  Follows require a new row regardless 
				$motification = new Motification;
				$motification->post_id = 0;
				$motification->noticed = 0;
				$motification->user_id = intval(Request::segment(3));//the person being notified.
				$motification->notification_type = 'follow';
				$motification->user = Auth::user()->username;//The follower's name
				$motification->users = array(Auth::user()->username);
				$motification->save();
				
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