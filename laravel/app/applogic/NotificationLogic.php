<?php namespace AppLogic\NotificationLogic;

//Replace with repositories when we can... 
use App,
	Auth,
	Queue,
	Motification,
	AppStorage\Post\PostRepository,
	AppStorage\Comment\CommentRepository,
	AppStorage\Notification\NotificationRepository
	;

class NotificationLogic {
	
	public function __construct() {
		$this->post = App::make('AppStorage\Post\PostRepository');
		$this->comment = App::make('AppStorage\Comment\CommentRepository');
		$this->not = App::make('AppStorage\Notification\NotificationRepository');
	}
	
	//Temporary notification getter for the top bar
	public function top($user_id) {
		return $this->not->limited($user_id);
	}
	
	/**
	 * New Post Notification for followers.
	 */
	public function post()
	{
		
	}
	
	/**
	 * Favorites Notifications.
	 */
	public function favorite($post_id) 
	{
		$post = $this->post->findById($post_id);
		
		$not = $this->not->find($post->id, $post->user->id, 'favorite');
		
		//Lot of this stuff still needs to be put in the repositories, but 1 step at a time!
		if(!$not) {
			$not = $this->not->instance();
			$not->post_id = $post->id;
			$not->post_title = $post->title;
			$not->post_alias = $post->alias;
			$not->user_id = $post->user->id;//Who this notification si going to.
			$not->noticed = 0;
			$not->notification_type = 'favorite';
			$not->save();
		}
		
		$not->push('users', Auth::user()->username,true);
	}
	
	public function unfavorite($post_id) {
		$user_id = Auth::user()->id;
		$post = $this->post->findById($post_id);
		
		$not = $this->not->find($post->id, $post->user->id, 'favorite'); 

		if($not != false) {
			$not->pull('users', Auth::user()->username);
			if(count($not->first()->users) == 0) {
				$not->delete();
			}
		}
	}

	public function follow($following) {
		//Below is an insert function.  Follows require a new row regardless 
		$not = $this->not->instance();
		$not->post_id = 0;
		$not->noticed = 0;
		$not->user_id = intval($other_user_id);//the person being notified.
		$not->notification_type = 'follow';
		$not->user = Auth::user()->username;//The follower's name
		$not->users = array(Auth::user()->username);
		$not->save();
	}

	public function unfollow($user_id) {
		$this->not->delete(
				$user_id, 
				null, 
				Auth::user()->username, 
				'follow'
				);
	}

	
	public function repost($post) {
		$not = $this->not->find($post->id, $post->user->id, 'repost');
				
		if(!$not) {
			$not = $this->not->instance();
			$not->post_id = $post->id;
			$not->post_title = $post->title;
			$not->post_alias = $post->alias;
			$not->user_id = $post->user->id;//Who this notification si going to.
			$not->noticed = 0;
			$not->notification_type = 'repost';
			$not->save();
		}
		$not->push('users', Auth::user()->username,true);
		
		//Add to follower's notifications
		Queue::push('UserAction@repost', 
					array(
						'post_id' => $post->id,
						'user_id' => Auth::user()->id,
						'username' => Auth::user()->username,
						)
					);
	}
	
	public function unrepost($post_id) {
		//Should we get rid fo the notification to the original?
		
		Queue::push('UserAction@delrepost', //maybe we need to rename one those these 2 methods to keep it consistent.
							array(
								'post_id' => $post_id,
								'user_id' => Auth::user()->id,
								'username' => Auth::user()->username,
								)
							);
	}
		
	/**
	 * Comment Notifications  (Replies too)
	 * @param object $post The Post object
	 * @param object $comment The Comment Object
	 */
	public function comment($post, $comment) 
	{
		$user_id = Auth::user()->id;	
		$username = Auth::user()->username;
		//Check to make sure that you don't own the post.
		if($post->user_id != $user_id) {
			
			$not = $this->not->find($post->id, $post->user->id, 'comment');
			
			if(!$not) {
				$not = $this->not->instance();
				$not->post_id = $post->id;
				$not->post_title = $post->title;
				$not->post_alias = $post->alias;
				$not->user_id = $post->user->id;//Who this notification si going to.
				$not->noticed = 0;
				$not->comment_id = $comment->id;
				$not->notification_type = 'comment';
				$not->save();
			} else {
				//if it exists, just update.
				$not->update(array('noticed' => 0));
			}
			$not->push('users', $username,true);
		}

		//If reply
		if($comment->parent_id != 0 ) {
			$orig_comment = $this->comment->findById($comment->parent_id);
			//Gotta make sure to not notify you replying to you.
			if($orig_comment->user_id != $user_id) {
				
				$not = $this->not->find($post->id, $orig_comment->user_id, 'reply');
				
				if(!$not) {
					$not = $this->not->instance();
					$not->post_id = $post->id;
					$not->post_title = $post->title;
					$not->post_alias = $post->alias;
					$not->user_id = $orig_comment->user_id;//Who this notification si going to.
					$not->noticed = 0;
					$not->comment_id = $comment->id;
					$not->notification_type = 'reply';
					$not->save();
				} else {
					//if it exists, just update.
					$not->update(array('noticed' => 0));
				}
				$not->push('users', $username, true);
				
			}
		}
	}
	
}