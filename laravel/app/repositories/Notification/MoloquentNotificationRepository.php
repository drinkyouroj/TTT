<?php namespace AppStorage\Notification;

use Motification ,DB;

class MoloquentNotificationRepository implements NotificationRepository {

	public function __construct(Motification $notification)
	{
		$this->not = $notification;
	}
	
	public function instance() {
		return new Motification;
	}
	
	// Creates a new notification accordingly. If the notification exists, push the action_user to the
	// array of users associated with this notification (ie: multiple users favoriting the same post).
	//
	// returns: the notification object.
	public function create($data, $action_user) {

		$not = self::find($data['post_id'], $data['user_id'], $data['notification_type']);

		// If the notification does not already exist, create one.
		if ( !$not ) {
			$not = self::instance();
			$not->notification_type = (!empty($data['notification_type'])) ? $data['notification_type'] : 'none';
			$not->post_id = (!empty($data['post_id'])) ? $data['post_id'] : 0;
			$not->noticed = (!empty($data['noticed'])) ? $data['noticed'] : 0;
			$not->user_id = (!empty($data['user_id'])) ? $data['user_id'] : 0;
			$not->user = (!empty($data['user'])) ? $data['user'] : '' ;
			$not->users = (!empty($data['users'])) ? $data['users'] : array() ;
			$not->comment_id = (!empty($data['comment_id'])) ? $data['comment_id'] : 0 ;
			$not->save();
		}

		// Push the action_user to the notification object
		self::pushUsers( $not, $action_user );

		return $not;
	}


	/**
	 * Returns a notification object if it exists
	 * @param integer $post_id Post ID
	 * @param integer $user_id User ID
	 * @param string $type Notification Type 
	 */
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
	
	public function allDesc($user_id) {
		return $this->not->where('user_id', $user_id)
							->orderBy('created_at','DESC')
							->get();
	}
	
	//Below is a temporary function until we go full async.
	public function limited($user_id) {
		return $this->not->where('user_id',$user_id)
							->where('notification_type', '!=', 'message')//message notification is different.
							->where('noticed',0)
							->take(7)//taking 7 for now.  We'll change this up when we do a full rest interfaced situation.
							->orderBy('updated_at', 'DESC')
							->get();
	}
	
	//Check
	public function check() {
		
	}

	//Update
	public function noticed($notification_ids, $user_id) {
		$this->not->where('user_id', $user_id)
					->whereIn('_id', $notification_ids)
					->update(array('noticed'=>1));
	}

	public function pushUsers($not, $username) {
		$not->push('users', $username, true);
	}
	
	public function pullUsers($not, $username) {
		if($not) {
			$not->pull('users', $action_user->username);
			//delete the notification all together if there are no other users left.
			if(count($not->first()->users) == 0) {
				$not->delete();
			}
		}
	}

	/**
	 * Delete a Notification
	 * @param integer $user_id User id for the receiving end
	 * @param integer $post_id Post associated with the notification
	 * @param string $username Username for the person giving the notificaiton
	 * @param string $type The type of Notificaiton
	 */
	public function delete($user_id, $post_id, $username,  $type) {
		$not = $this->not
					->where('user_id', intval($user_id))//sometimes request stuff comes in as string.
					->where('user', $username)
					->where('notification_type', $type);
					if($type != 'follow'|| $post_id == null) {
						$not = $not->where('post_id', $post_id);
					}
					
		$not->delete();
	}
	
	
}