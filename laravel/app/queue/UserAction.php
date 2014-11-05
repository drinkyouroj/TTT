<?php

use \App;

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
		$this->follow = App::make('AppStorage\Follow\FollowRepository');
		$this->activity = App::make('AppStorage\Activity\ActivityRepository');
		$this->feed = App::make('AppStorage\Feed\FeedRepository');
	}
	
	/**
	 * Notify all followers that you reposted
	 * @param object $job the queue job object.
	 * @param array $data Data that's needed for the job.
	 */
	function repost($job, $data) {
		
		//Grab all the followers for this user
		$followers = $this->follow->followersByUserId($data['user_id']);

		$post = $this->post->findById($data['post_id']);
		
		$action_user = User::where('id', $data['user_id'])->first();
		
			foreach($followers as $follower) {
				
				//below statement is to ensure that the user who owns the content doesn't get the repost.
				if($follower->follower_id != $post->user_id) {

					//New Feed System replaces the old activity;
					$new_feed = array(
							'user_id' => $follower->follower_id,
							'post_title' => $post->title,
							'post_id' => $data['post_id'],
							'feed_type' => 'repost',
							'users' => $action_user->username
							);
					$this->feed->create($new_feed);
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
		$followers = $this->follow->followersByUserId($data['user_id']);
		
		$post = $this->post->findById($data['post_id']);
		
		$action_user = User::where('id', $data['user_id'])->first();
		
		foreach($followers as $follower) {
			//below statement is to ensure that the user who owns the content doesn't get the repost.
			if($follower->follower_id != $post->user->id) {

				$del_feed = array(
						'user_id' => $follower->follower_id,
						'post_id' => $data['post_id'],
						'feed_type' => 'repost',
						'users' => $action_user->username
						);
				$this->feed->delete($del_feed);
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
		$followers = $this->follow->followersByUserId($data['user_id']);
		
		$post = $this->post->findById($data['post_id']);
		
		//process notification for each follower
		foreach($followers as $follower) {

			$new_feed = array(
					'user_id' => $follower->follower_id,
					'post_title' => $post->title,
					'post_id' => $data['post_id'],
					'feed_type' => 'post',
					'users' => $data['username']
					);
			$this->feed->create($new_feed);

		}
		
		$job->delete();
		
	}
	
}