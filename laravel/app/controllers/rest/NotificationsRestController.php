<?php
//This is for your Inbox
class NotificationsRestController extends \BaseController {
	
	//Gets a collection of notification for the current user.
	public function index() {
		//Grab the entire unnoticed stack
		$mots = Motification::where('user_id', Auth::user()->id)
					->where('noticed', 0)
					->get();
		if(count($mots)) {
			return Response::json(
				array('result' => $mots->toArray()),
				200
			);
		} else {
			return Response::json(
				array('result' => 0),
				200
			);
		}
		
	}
	
	//Gets a specific notification
	public function show() {
		
	}
	
	//Marks notifications as read 
	public function store() {
		$notification_ids = Input::get('notification_ids');
		if(is_array($notification_ids)) {
			Motification::where('user_id', Auth::user()->id)
						->whereIn('_id', $notification_ids)
						->update(array('noticed'=>1));
						
			return Response::json(
				array('result'=>'success'),
				200//response is OK!
			);
		} else {
			return Response::json(
				array('result'=>'not array'),
				200//response is OK!
			);
		}
		
	}
	
}