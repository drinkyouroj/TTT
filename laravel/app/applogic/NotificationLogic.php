<?php namespace AppLogic\NotificationLogic;

//Replace with repositories when we can... 
use Notification, Motification, Auth, Post, Comment;

class NotificationLogic {
	
	/**
	 * New Post Notification for followers.
	 */
	public function post()
	{
		
	}
		
	public function favorite() {}
	
	public function repost() {}
		
	/**
	 * Comment Notifications  (Replies too)
	 * @param object $post The Post object
	 * @param object $comment The Comment Object
	 */
	public function comment($post, $comment) 
	{	
		//Check to make sure that you don't own the post.
		if($post->user_id != Auth::user()->id) {
			//TODO Get rid of this once we're done testing the new mongo notifications.
			$notification = new Notification;
			$notification->post_id = $post->id;
			$notification->user_id = $post->user->id;
			$notification->action_id = Auth::user()->id;
			$notification->notification_type = 'comment';
			$notification->comment_id = $comment->id;
			$notification->save();
			
			$mot = Motification::where('post_id', $post->id)//Post id
								->where('user_id', $post->user->id)//person getting notified
								->where('notification_type', 'comment');
			
			if(!$mot->count()) {
				$mot = new Motification;
				$mot->post_id = $post->id;
				$mot->post_title = $post->title;
				$mot->post_alias = $post->alias;
				$mot->user_id = $post->user->id;//Who this notification si going to.
				$mot->noticed = 0;
				$mot->comment_id = $comment->id;
				$mot->notification_type = 'comment';
				$mot->save();
			} else {
				//if it exists, just update.
				$mot->update(array('noticed' => 0));
			}
			$mot->push('users', Auth::user()->username,true);
			//Should the comment counter be incremented if you're the owner? no!
			Post::where('id', $post->id)->increment('comment_count',1);
		}

		//If reply
		if($comment->parent_id != 0 ) {
			$orig_comment = Comment::where('id', $comment->parent_id)->first();
			//Gotta make sure to not notify you replying to you.
			if($orig_comment->user_id != Auth::user()->id) {
				//TODO get rid of the original notifcation code soon.
				$reply = new Notification;
				$reply->post_id = $post->id;
				$reply->user_id = $orig_comment->user_id;
				$reply->action_id = Auth::user()->id;
				$reply->notification_type = 'reply';
				$reply->comment_id = $comment->id;
				$reply->save();
				
				$mot = Motification::where('post_id', $post->id)//Post id									
									->where('user_id', $orig_comment->user_id)//person getting notified
									->where('notification_type', 'reply');
				
				if(!$mot->count()) {
					$mot = new Motification;
					$mot->post_id = $post->id;
					$mot->post_title = $post->title;
					$mot->post_alias = $post->alias;
					$mot->user_id = $orig_comment->user_id;//Who this notification si going to.
					$mot->noticed = 0;
					$mot->comment_id = $comment->id;
					$mot->notification_type = 'reply';
					$mot->save();
				} else {
					//if it exists, just update.
					$mot->update(array('noticed' => 0));
				}
				$mot->push('users', Auth::user()->username,true);
				
			}
		}
	}
	
	//Maybe below is not required?? we'll see
	public function message() {}
	
}