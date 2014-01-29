<?php
class RepostRestController extends \BaseController {
	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//First, place this in the profile posts table for the user.
			
		//Gotta look up the current user's follower IDs
		
		//Go through each follower and add it to their profile posts table also.
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show()
	{
		$exists = Repost::where('post_id', '=', Request::segment(3))
						->where('user_id', '=', Auth::user()->id)
						->count();
		
		if(!$exists) {//Doesn't exists
			//Crete a new follow
			$repost = new Repost;
			$repost->post_id = Request::segment(3);
			$repost->user_id = Auth::user()->id;//Gotta be from you.
			$repost->save();
			if($repost->id) {
				//Add to the OP's notification
				$notification = new Notification;
				$notification->post_id = Request::segment(3);
				$notification->user_id = Auth::user()->id;
				$notification->notification_type = 'favorite';
				$notification->save();
				
				//Add to follower's notifications
				Queue::push('UserAction@repost', 
							array(
								'post_id' => Request::segment(3),
								'user_id' => Auth::user()->id
								)
							);
				
				//Add to profile
				
				
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

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

}