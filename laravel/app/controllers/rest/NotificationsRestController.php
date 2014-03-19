<?php
//This is for your Inbox
class NotificationsRestController extends \BaseController {
	
	public function index() {}
	
	//Marks notifications as read 
	public function store() {
		$notification_ids = Input::get('notification_ids');
		if(is_array($notification_ids)) {
			Notification::where('user_id', Auth::user()->id)
						->whereIn('id', $notification_ids)
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