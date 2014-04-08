<?php
/**
 * This is a Helper for simplifiying the Motification (mongo notification)
 */
class MotificationHelper {
	
	function __construct($argument) {
		
	}
	
	/*
	 * $data contains an array of information required for the action.
	 * $data['post_id'] = post id
	 * $data['notification_type'] = notification type
	 * $data['user'] = Auth::user()->username;
	 */
	public static function newMotification($data) {
		if($data['notification_type'] == 'follow') {
		//Follow
			$post_title = false;//no title for a follow.
			$user_id = $data['user_id'];
			$motification = false;//empty array!
		} elseif($data['post_id']) {
		//Post, like, favorite, repost
			$post = Post::where('id', $data['post_id'])->first();
			$post_title = $post->title;
			
			//If its a repost situation, we want to let the $data handle who the notification goes to.
			if($data['notification_type'] == 'repost' || $data['notification_type'] == 'post' ) {
				$user_id = $data['user_id'];
				
			} else {
				$user_id = $post->user->id;
			}
			$motification = Motification::where('post_id', $data['post_id'])
										->where('user_id', $user_id)
										->where('notification_type', $data['notification_type']);
										//->push('users', array('user'=>$data['user']));
			
		}
		
		//If we can't find it, we need to make it.  Or If its a follow situation, always make a new notification.
		if(!$motification) {

			unset($motification);
			$motification = new Motification;
			$motification->post_id = $data['post_id'];
			if($post_title) {
				$motification->post_title = $post_title;
			} else {
				$motification->username = $data['user'];//faster than seeking through embedded docs.
			}
			$motification->user_id = $post_user_id;//Who this notification si going to.
			$motification->noticed = 0;
			$motification->notification_type = $data['notification_type'];
			$motification->save();
		}
		$motification->push('users',$data['user'],true);//Who the notification is coming from.
	}
	
	/*
	 * 
	 * $data['post_id'] = post id
	 * $data['notification_type'] = notification type
	 * $data['user'] = Auth::user()->username;
	 */
	public static function delMotification($data) {
		if($data['notification_type'] == 'follow') {
			$post_title = false;//no title for a follow.
			$motification = Motification::where('user_id', $data['user_id'])
									->where('username', $data['user'])
									->where('notification_type', $data['notification_type']);
									
		} else {
			//Like, Favorite, Repost
			$post = Post::where('id', $data['post_id'])->first();
			$motification = Motification::where('post_id', $data['post_id'])
										->where('user_id', $post->user->id)
										->where('notification_type', $data['notification_type']);
			if($motification->count()) {
				$motification->pull('users', $data['user']);
				//if after pulling the user, and the users array happens to be empty, we need to delete this notification entirely.
				if(empty($motification->users)) {
					$motification->delete();
				}
			}	
		}
		
		
		
		return true;
	}
	
}
