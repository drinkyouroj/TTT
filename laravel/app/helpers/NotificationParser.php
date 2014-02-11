<?php
/**
 * This class is here so that we can use it to parse the notification data.
 */
class NotificationParser {
	
	/**
	 * Pass the notification data and returns the grouped situation.
	 */
	public static function parse($notifications) {
		
		//Gotta parse the $notifications here:
		$compiled = array();
		//First push all the notificaiton IDs into the post array according to their post id.
		foreach($notifications as $k => $not) {
			
			if(!isset($compiled[$not->post_id][$not->notification_type])) {
				$compiled[$not->post_id][$not->notification_type] = array($not);
			}else {
				array_push($compiled[$not->post_id][$not->notification_type], $not);
			}
		}
		
		return $compiled;
	}
	
}
