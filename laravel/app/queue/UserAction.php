<?php

use \App, 
	AppStorage\Post\PostRepository,
	AppStorage\Notification\NotificationRepository;

/**
 * User Queue Actions
 */
class UserAction {
	
	/**
	 * Constructor
	 */
	public function __construct() {
		$this->post = App::make('AppStorage\Post\PostRepository');
		$this->not = App::make('AppStorage\Notification\NotificationRepository');
	}
	
	/**
	 * Notify all followers that you reposted
	 * @param object $job the queue job object.
	 * @param array $data Data that's needed for the job.
	 */
	function repost($job, $data) {
		
		//Grab all the followers for this user
		$followers = Follow::where('user_id','=', $data['user_id'])
						->get();
		$post = $this->post->findById($data['post_id']);
		
		$action_user = User::where('id', $data['user_id'])->first();
		
			//process notification for each follower
			foreach($followers as $follower) {
				
				$not = $this->not->find(
									$data['post_id'], //Post id	
									$follower->follower_id, //person getting notified
									'repost'
									);
									
				//below still needs development.
				if(!$not) {
					$not = $this->not->instance();
					$not->post_id = $data['post_id'];
					$not->post_title = $post->title;
					$not->post_alias = $post->alias;
					$not->user_id = $follower->follower_id;//Who this notification si going to.
					$not->noticed = 0;
					$not->notification_type = 'repost';
					$not->save();
				}
				$not->push('users', $data['username'],true);
				
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
		$followers = Follow::where('user_id', $data['user_id'])
						->get();
		
		$post = $this->post->findById($data['post_id']);
		
		$action_user = User::where('id', $data['user_id'])->first();
		
		//process notification for each follower
		foreach($followers as $follower) {
			$not = $this->not->find(
							$data['post_id'], 
							$follower->follower_id, 
							'repost'
							);
			
			if($not != false) {
				$not->pull('users', $action_user->username);
				if(count($not->first()->users) == 0) {
					$not->delete();
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
		$followers = Follow::where('user_id', $data['user_id'])
						->get();
		
		$post = $this->post->findById($data['post_id']);
		
		//process notification for each follower
		foreach($followers as $follower) {
			
			$not = $this->not->find(
								$data['post_id'],
								$follower->follower_id,
								'post'
								);
				
			if($not != false) {
				$not = $this->not->instance();
				$not->post_id = $data['post_id'];
				$not->post_title = $post->title;
				$not->post_alias = $post->alias;
				$not->user_id = $follower->follower_id;//Who this notification si going to.
				$not->noticed = 0;
				$not->notification_type = 'post';
				$not->save();
			}
			$not->push('users', $data['username'], true);
			
			
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