<?php namespace AppLogic\NotificationLogic;

//Replace with repositories when we can... 
use App,
	Auth,
	Queue,
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
	
	// Fetch the last few notifications to pre-populate the view
	public function top ( $user_id ) {
		return $this->not->getByUserId( $user_id, 1, 10, true );
	}

	public function getUnreadCount ( $user_id ) {
		return $this->not->unreadNotificationCount( $user_id );
	}
	
	/**
	 * New Post Notification for followers.
	 */
	public function post()
	{
		
	}
	
	/**
	 * 	Favorites Notifications.
	 * 	@param $post_id: The post being favorited
	 */
	public function favorite ( $post_id )
	{
		// Find the post
		$post = $this->post->findById( $post_id );
		// Construct the notification params
		$not_params = array(
			'post_id' => $post->id,
			'post_title' => $post->title,
			'post_alias' => $post->alias,
			'user_id' => $post->user->id,
			'notification_type' => 'favorite'
		);
		// Create the notification
		$this->not->create( $not_params, Auth::user()->username );
	}
	
	/**
	 *	Unfavorite a Notification.
	 * 	@param $post_id: The post being unfavorited
	 */
	public function unfavorite ( $post_id ) {
		// Find the post
		$post = $this->post->findById($post_id);
		// Find the notification ( if any )
		$not = $this->not->find($post->id, $post->user->id, 'favorite');
		// Pull the user from the array of users attached to this notification
		$this->not->pullUsers($not, Auth::user()->username);
	}

	/**
	 *	Follow User Notifcation
	 *  @param $other_user_id: The user being followed
	 */
	public function follow ( $other_user_id ) {
		// Construct the notification params
		$not_params = array(
			'user' => Auth::user()->username,  // The follower's name
			'user_id' => intval( $other_user_id ),  // The person being notified
			'post_id' => 0,  // post_id == 0 for all notifications of type 'follow'
			'notification_type' => 'follow'
		);
		// Create the notification
		$this->not->create( $not_params, Auth::user()->username );
	}

	/**
	 *	Unfollow User Notification
	 *  @param $user_id: The user being unfollowed
	 */
	public function unfollow ( $user_id ) {
		// Delete the notification 
		$this->not->delete(
			$user_id,
			null,
			Auth::user()->username, 
			'follow'
		);
	}

	/**
	 *	Repost Notification
	 *  @param $post_id: The post being reposted
	 */
	public function repost ( $post_id ) {
		// Find the post
		$post = $this->post->findById( $post_id );

		// Construct the notification params
		$not_params = array(
			'post_id' => $post->id,
			'post_title' => $post->title,
			'post_alias' => $post->alias,
			'user_id' => $post->user->id,
			'notification_type' => 'repost'
		);
		// Create the notification
		$this->not->create( $not_params, Auth::user()->username );
		
		//Add to follower's notifications
		Queue::push('UserAction@repost', 
					array(
						'post_id' => $post->id,
						'user_id' => Auth::user()->id,
						'username' => Auth::user()->username,
						)
					);
	}
	
	/**
	 *	Un-repost Notification
	 *  @param $post_id: The post being un-reposted
	 */
	public function unrepost ( $post_id ) {
		//Should we get rid of the notification to the original?
		

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
	public function comment ( $post, $comment ) {
		$user_id = Auth::user()->id;	
		$username = Auth::user()->username;
		// Check to make sure that you don't own the post.
		if ( $post->user_id != $user_id ) {
			// Construct the Notification params...
			$not_params = array(
				'post_id' => $post->id,
				'post_title' => $post->title,
				'post_alias' => $post->alias,
				'user_id' => $post->user->id,
				'noticed' => 0,
				'comment_id' => $comment->id,
				'notification_type' => 'comment'
			);
			$this->not->create( $not_params, $username );
		}

		//  If reply
		if ( $comment->parent_comment != null ) {
			$orig_comment = $this->comment->findById( $comment->parent_comment );
			//Gotta make sure to not notify you replying to you.
			if ( $orig_comment->author['user_id'] != $user_id ) {
				
				$not_params = array(
					'post_id' => $post->id,
					'post_title' => $post->title,
					'post_alias' => $post->alias,
					'user_id' => $orig_comment->author['user_id'],
					'noticed' => 0,
					'comment_id' => $comment->id,
					'notification_type' => 'reply'
				);

				$this->not->create( $not_params, $username );
			}
		}
	}
	
}