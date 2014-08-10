<?php
class NotificationsRestController extends \BaseController {
	
	public function __construct(NotificationRepository $not) {
		$this->not = $not;
	}
	
	public function index() {
		
	}

	//Gets a specific notification
	public function show() {
		
	}
	
	//Marks notifications as read 
	public function store() {
		$notification_ids = Input::get('notification_ids');//Its a GET situation on AJAX
		if(is_array($notification_ids)) {
			
			$this->not->noticed($notification_ids, Auth::user()->id);
						
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