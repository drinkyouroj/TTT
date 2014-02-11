<?php
class UserAction {
	
	
	/**
	 * Notify all followers that you reposted
	 */ 
	function repost($job, $data) {
		
		//Grab all the followers for this user
		$followers = Follow::where('user_id','=', $data['user_id'])
						->get();
		
		//process notification for each follower
		foreach($followers as $follower) {
			$noti = new Notification;
			$noti->action_id = $data['user_id'];//notification from user
			$noti->user_id = $follower->follower_id;
			$noti->post_id = $data['post_id'];
			$noti->notification_type = 'repost';
			$noti->save();
			
			$activity = new Activity;
			$activity->action_id = $data['user_id'];//notification from user
			$activity->user_id = $follower->follower_id; 
			$activity->post_id = $data['post_id'];
			$activity->type = 'repost';
			$activity->save();
			
		}
		
		$job->delete();
	}
	
	/**
	 * Let everyone know you posted something new
	 */
	function newpost($job, $data) {
		//Grab all the followers for this user
		$followers = Follow::where('user_id','=', $data['user_id'])
						->get();
		
		//process notification for each follower
		foreach($followers as $follower) {
			$noti = new Notification;
			$noti->action_id = $data['user_id'];//notification from user
			$noti->user_id = $follower->follower_id;
			$noti->post_id = $data['post_id'];
			$noti->notification_type = 'new';
			$noti->save();
			
			$activity = new Activity;
			$activity->action_id = $data['user_id'];//notification from user
			$activity->user_id = $follower->follower_id; 
			$activity->post_id = $data['post_id'];
			$activity->type = 'new';
			$activity->save();
		}					
		
		$job->delete();
		
	}
	
}