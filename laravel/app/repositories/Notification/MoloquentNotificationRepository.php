<?php namespace AppStorage\Notification;

use Motification ,DB, Request;

class MoloquentNotificationRepository implements NotificationRepository {

	public function __construct(Motification $notification)
	{
		$this->not = $notification;
	}
	
	public function instance() {
		return new Motification;
	}
	
	public function input($user_id) {}

	//Create
	public function create($input) {}

	//Read
	public function find($post_id, $user_id, $type) {
		$not = $this->not->where('post_id', $post_id)//Post id
					->where('user_id', $user_id)//person getting notified
					->where('notification_type', $type);
		return self::count_check($not);
	}
	
	public function findById($id) {}
	
		private function count_check($not) {
			if($not->count()) {
				return $not;
			} else {
				return false;
			}
		}
	
	
	//Read Multi
	public function all() {}
	
	//Check
	public function check() {}

	//Delete
	public function delete($id) {}
	
	
}