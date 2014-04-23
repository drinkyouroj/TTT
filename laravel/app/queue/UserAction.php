<?php
/**
 * User Queue Actions
 */
class UserAction {
	
	/**
	 * Notify all followers that you reposted
	 * @param object $job the queue job object.
	 * @param array $data Data that's needed for the job.
	 */
	function repost($job, $data) {
		
		//Grab all the followers for this user
		$followers = Follow::where('user_id','=', $data['user_id'])
						->get();
		$post = Post::where('id', $data['post_id'])->first();
		
		$action_user = User::where('id', $data['user_id'])->first();
		
		//process notification for each follower
		foreach($followers as $follower) {
			//TODO get rid of the original notifcation code soon.
			$noti = new Notification;
			$noti->action_id = $data['user_id'];//notification from user
			$noti->user_id = $follower->follower_id;
			$noti->post_id = $data['post_id'];
			$noti->notification_type = 'repost';
			$noti->save();
			
			$mot = Motification::where('post_id', $data['post_id'])//Post id									
									->where('user_id', $follower->follower_id)//person getting notified
									->where('notification_type', 'repost');
				
				if(!$mot->count()) {
					$mot = new Motification;
					$mot->post_id = $data['post_id'];
					$mot->post_title = $post->title;
					$mot->post_alias = $post->alias;
					$mot->user_id = $follower->follower_id;//Who this notification si going to.
					$mot->noticed = 0;
					$mot->notification_type = 'repost';
					$mot->save();
				}
			$mot->push('users', $data['username'],true);
			
			//below statement is to ensure that the user who owns the content doesn't get the repost.
			if($follower->follower_id != $post->user->id) {
				$activity = new Activity;
				$activity->action_id = $data['user_id'];//notification from user
				$activity->user_id = $follower->follower_id; 
				$activity->post_id = $data['post_id'];
				$activity->post_type = 'repost';
				$activity->save();
			}
		}
		
		$job->delete();
	}

	/**
	 * Deletes reposts
	 * @param object $job the queue job object.
	 * @param array $data Data that's needed for the job.
	 */
	function delrepost($job, $data) {
		
		//Grab all the followers for this user
		$followers = Follow::where('user_id','=', $data['user_id'])
						->get();
		$post = Post::where('id', $data['post_id'])->first();
		
		$action_user = User::where('id', $data['user_id'])->first();
		
		//process notification for each follower
		foreach($followers as $follower) {
			Notification::where('action_id', $data['user_id'])
						->where('post_id', $data['post_id'])
						->where('notification_type', 'repost')
						->where('user_id',$follower->follower_id)//variance
						->delete();
			
			$mot = Motification::where('post_id', $data['post_id'])
						->where('notification_type', 'repost')
						->where('user_id',$follower->follower_id);
			if($mot->count() >= 1) {
				$mot->pull('users', $action_user->username);
				if(count($mot->first()->users) == 0) {
					$mot->delete();
				}
			}
			
			//below statement is to ensure that the user who owns the content doesn't get the repost.
			if($follower->follower_id != $post->user->id) {
				
				Activity::where('action_id', $data['user_id'])
						->where('user_id', $follower->follower_id)
						->where('post_id', $data['post_id'])
						->where('post_type', 'repost')
						->delete();
			}
		}
		$job->delete();
	}

	
	/**
	 * Let's everyone know you posted something new to your followers
	 * @param object $job the queue job object.
	 * @param array $data Data that's needed for the job.
	 */
	function newpost($job, $data) {
		//Grab all the followers for this user
		$followers = Follow::where('user_id','=', $data['user_id'])
						->get();
		
		$post = Post::where('id', $data['post_id'])->first();
		
		
		//process notification for each follower
		foreach($followers as $follower) {
			//TODO Get rid of the original notification code soon.
			$noti = new Notification;
			$noti->action_id = $data['user_id'];//notification from user
			$noti->user_id = $follower->follower_id;
			$noti->post_id = $data['post_id'];
			$noti->notification_type = 'post';
			$noti->save();
			
			
			$mot = Motification::where('post_id', $data['post_id'])//Post id									
								->where('user_id', $follower->follower_id)//person getting notified
								->where('notification_type', 'post');
				
			if(!$mot->count()) {
				$mot = new Motification;
				$mot->post_id = $data['post_id'];
				$mot->post_title = $post->title;
				$mot->post_alias = $post->alias;
				$mot->user_id = $follower->follower_id;//Who this notification si going to.
				$mot->noticed = 0;
				$mot->notification_type = 'post';
				$mot->save();
			}
			$mot->push('users', $data['username'],true);
			
			
			$activity = new Activity;
			$activity->action_id = $data['user_id'];//notification from user
			$activity->user_id = $follower->follower_id; 
			$activity->post_id = $data['post_id'];
			$activity->post_type = 'post';
			$activity->save();
		}					
		
		$job->delete();
		
	}
	
}