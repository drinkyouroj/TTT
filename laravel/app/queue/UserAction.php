<?php
class UserAction {
	
	
	/**
	 * Nofity all followers that you posted a new post
	 */
	function publish($job, $data) {
		
		
		$job->delete;
	}
	
	
	/**
	 * Notify all followers that you reposted
	 */ 
	function repost($job, $data) {
		
		//grab the post being reposted
		$post = Post::where('id', '=', $data['post_id'])->first();
		
		//Grab all the followers for this user
		$followers = Follow::where('user_id','=', $data['user_id'])
						->get();
		
		//process notification for each follower
		foreach($followers as $follower) {
			$noti = new Notification;
			$noti->action_id = $data['user_id'];//notification from user
			$noti->owner_id = $follower->follower_id;
			$noti->post_id = $post->id;
			$noti->notification_type = 'repost';
			$noti->save();
		}					
		
		$job->delete;
	}
	
}