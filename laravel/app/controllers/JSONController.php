<?php
//Combines a lot of the older rest controller functions which did not require the entire REST Put and DEL stuff.
class JSONController extends BaseController {

	public function __construct(
						PostRepository $post,
						CommentRepository $comment,
						NotificationRepository $not,
						FollowRepository $follow,
						RepostRepository $repost,
						LikeRepository $like,
						FavoriteRepository $favorite,
						ProfilePostRepository $profilepost,
						ActivityRepository $activity,
						FeedRepository $feed,
						PostFlaggedRepository $postFlagged,
						FlaggedContentRepository $flaggedContent,
						UserRepository $user
						) {
		$this->post = $post;
		$this->not = $not;
		$this->comment = $comment;
		$this->follow = $follow;
		$this->repost = $repost;
		$this->like = $like;
		$this->favorite = $favorite;
		$this->profilepost = $profilepost; //This might change its name to feed or something.
		$this->activity = $activity;
		$this->feed = $feed;
		$this->postFlagged = $postFlagged;
		$this->flaggedContent = $flaggedContent;
		$this->user = $user;
	}

	//Like or Unlike a Post
	public function getLikes() {
		if(Request::segment(3)) {

			$post_id = Request::segment(3);
			$user_id = Auth::user()->id;

			$exists = $this->like->exists($user_id, $post_id);
							
			$owns = $this->post->owns($post_id, $user_id);
			
			if(!$exists && !$owns) {//Doesn't exists
				//Crete a new Like
				$like = $this->like->create($user_id, $post_id);

				$this->post->incrementLike(Request::segment(3));

				NotificationLogic::like($post_id);
				AnalyticsLogic::createSessionEngagement( 'like' );
				
				if($like->id) {
					return Response::json(
						array('result'=>'success'),
						200//response is OK!
					);
				}
			} elseif($exists) {//Relationship already exists
			
				$this->like->delete($user_id, $post_id);
				
				$this->post->decrementLike(Request::segment(3));

				NotificationLogic::unlike($post_id);
				AnalyticsLogic::createSessionEngagement( 'unlike' );
				
				return Response::json(
					array('result'=>'deleted'),
					200//response is OK!
				);
			}
		}
		return Response::json(
			array('result'=>'fail'),
			200//response is OK!
		);
	}

	//Mark as Read
	public function postNotification() {
		$notification_ids = Input::get('notification_ids');//Its a GET situation on AJAX
		if(is_array($notification_ids)) {
			
			$this->not->noticed($notification_ids, Auth::user()->id);
			AnalyticsLogic::createSessionEngagement( 'notification-read' );

			return Response::json(
				array('result'=>'success'),
				200//response is OK!
			);
		} else {
			return Response::json(
				array('result'=>'not array'),
				200//response is OK!
			);
		}
	}

	//Mark a post as a Favorite (aka Save)
	public function getFavorites() {
		if(Request::segment(3) != 0) {
			
			$user_id = Auth::user()->id;
			$post_id = Request::segment(3);

			$exists = $this->favorite->exists($user_id, $post_id);
							
			$owns = $this->post->owns($post_id, $user_id);
			$post = $this->post->findById($post_id);
			
			if (!$exists && !$owns) {//Relationship doesn't exist and the user doesn't own this.
				
				//Add to favorite.
				$this->favorite->create($user_id, $post_id);
				AnalyticsLogic::createSessionEngagement( 'save' );

				return Response::json(
					array('result'=>'success'),
					200//response is OK!
				);
			
			} elseif ($exists) {//Relationship already exists, should this be an unfavorite?
				
				//Delete from Favorites
				$this->favorite->delete($user_id, $post_id);
				AnalyticsLogic::createSessionEngagement( 'unsave' );

				return Response::json(
					array('result'=>'deleted'),
					200//response is OK!
				);
			}
		}
		return Response::json(
			array('result'=>'fail'),
			200//response is OK!
		);
	}

	//Let's you follow someone
	public function getFollows() {
		//request sometimes comes in as a string
		$other_user_id = intval(Request::segment(3));
		$my_user_id = Auth::user()->id;
		$my_username = Auth::user()->username;
		
		if($other_user_id && !empty($other_user_id)) {
			
			$exists = $this->follow->exists($other_user_id, $my_user_id);
			
			if($exists) {//Relationship already exists

				$this->follow->delete($other_user_id, $my_user_id);
				
				NotificationLogic::unfollow($other_user_id);
				AnalyticsLogic::createSessionEngagement( 'unfollow' );

				return Response::json(
					array('result'=>'deleted'),
					200//response is OK!
				);
			} else {//Doesn't exists
				//Crete a new follow
				$this->follow->create($other_user_id, $my_user_id);
				
				NotificationLogic::follow($other_user_id);
				AnalyticsLogic::createSessionEngagement( 'follow' );

				return Response::json(
					array('result'=>'success'),
					200//response is OK!
				);
			}
		}
	
		return Response::json(
			array('result'=>'fail'),
			200//response is OK!
		);
	}

	//Gets followers for a given user id.
	public function getFollowers($id) {
		if($id) {
			return Response::json(
				array(
					'followers'=> $this->follow->jsonFollowers($id)
					),
				200//response is OK!
			);
			
		} else {
			return Response::json(
				array('followers'=>'fail'),
				200//response is OK!
			);
		}
	}

	//Gets following for a given user id
	public function getFollowing($id) {
		if($id) {
			return Response::json(
				array(
					'following'=> $this->follow->jsonFollowing($id)
					),
				200//response is OK!
			);
		} else {
			return Response::json(
				array('following'=>'fail'),
				200//response is OK!
			);
		}
	}

	//  Reposts a certain post.
	public function getReposts() {
		if(Request::segment(3) != 0) {

			$user_id = Auth::user()->id;
			$post_id = Request::segment(3);
			$post = $this->post->findById($post_id);

			//Does this relationship already exist?
			$exists = $this->repost->exists($user_id, $post_id);

			//You can't repost your own stuff.
			$owns = $this->post->owns($post_id, $user_id);
			
			if( !$exists && !$owns ) {  //Doesn't exists and you don't own it.
				// Repost!
				$this->repost->create( $user_id, $post_id );

				//Add to the profile Collection
				$new_profilepost = array(
						'post_id' => $post->id,
						'profile_id' => $user_id,
						'user_id' => $post->user->id,
						'post_type' => 'repost'
					);

				//Add to Feed
				$this->profilepost->create($new_profilepost);

				// Notify the repost to owner
				NotificationLogic::repost( $post_id );
				AnalyticsLogic::createSessionEngagement( 'repost' );

				//This has to be outside 
				return Response::json(
					array('result'=>'success'),
					200//response is OK!
				);
			} elseif ( $exists ) {  //Relationship already exists
				// Delete the repost!
				$this->repost->delete( $user_id, $post_id );
				
				//Delete from the Profile
				$del_profilepost = array(
						'post_id' => $post->id,
						'profile_id' => $user_id,
						'user_id' => $post->user->id,
						'post_type' => 'repost'
					);
				$this->profilepost->delete($del_profilepost);

				// Delete the notification of the repost					
				NotificationLogic::unrepost( $post_id );
				AnalyticsLogic::createSessionEngagement( 'unrepost' );
				
				return Response::json(
					array('result'=>'deleted'),
					200//response is OK!
				);
			}
		}

		return Response::json(
			array('result'=>'fail'),
			200//response is OK!
		);
	}

	//Sets posts as featured.
	public function getFeature() {
		$owns = $this->post->owns($id, Auth::user()->id);
		
		if($owns) {
			User::where('id', Auth::user()->id)
				->update(array('featured'=> $id));
				
			Session::put('featured', $id);
			AnalyticsLogic::createSessionEngagement( 'featured-set' );

			return Response::json(
					array('result'=>'success'),
					200//response is OK!
				);
		} else {
			return Response::json(
					array('result'=>'fail'),
					200//response is OK!
				);
		}
	}

	//Delete a Comment
	public function getComments($id) {
		$user_id = Auth::user()->id;	
		$owns = $this->comment->owns($id, $user_id);

		if( $owns || Auth::user()->hasRole('Moderator') ) {
			$this->comment->unpublish($id, $user_id);  //unpublished = deleted.
			AnalyticsLogic::createSessionEngagement( 'comment-delete' );

			return Response::json(
				array(
					'result' => 'deleted'
				),
				200//response is OK!
			);
		} else {
			return Response::json(
				array(
					'result' => 'failed'
				),
				200//response is OK!
			);
		}
	}

	//Delete Users ( deactivate accounts )
	public function postUserdelete() {
		$id = Input::has('id') ? Input::get('id') : null;
		$password = Input::has('password') ? Input::get('password') : null;
		//Was the ID passed and is it the Authenticated user?
		if ( $id && Auth::user()->id == $id ) {

			if ( Auth::validate(array( 'id' => $id, 'password' => $password )) ) {
				$this->user->delete( $id );	
				AnalyticsLogic::createSessionEngagement( 'account-deactivate' );

				return Response::json(
					array('success'=>true),
					200//response is OK!
				);
			} else {
				// Invalid password
				return Response::json(
					array('error'=>'Incorrect password.'),
					200//response is OK!
				);
			}
			
		} else {
			return Response::json(
				array('error'=>'Invalid permissions to perform this action.'),
				200//response is OK!
			);
		}
	}

	public function getPostdelete($id) {
		$user_id = Auth::user()->id;
		$owns = $this->post->owns($id, $user_id);
		
		if($owns) {
			
			//Grab the post for rest of this.
			$post = $this->post->findById($id);
			
			//Delete Scenario
			if($post->published) {

				//if the post was featured set it back to nothing.
				if($post->featured) {
					User::where('id', $user_id)->update(array('featured'=>0));
				}
				
				//unpublish the post.
				$this->post->unpublish($id);
				AnalyticsLogic::createSessionEngagement( 'post-delete' );

				//Take it out of the activities. (maybe queue this too?)
				$this->activity->deleteAll($user_id, $id);
								
				//Gotta get rid of it from the MyPosts/External Profile View 
				$this->profilepost->delete($user_id,$post, 'post');
				
				return Response::json(
						array('result' =>'unpublished'),
						200//response is OK!
					);
			} else {
				//UnDelete Scenario
				$this->post->publish($id);

				$this->profilepost->publish($user_id, $post->id);
		
				return Response::json(
						array('result' =>'republished'),
						200//response is OK!
					);
			}
		} else {
			return Response::json(
					array('result' =>'fail'),
					200//response is OK!
				);
		}
	}

	public function getRead($post_id) {
		if(Auth::check()) {
			$user = Auth::user();
			$this->favorite->read($user->id, $post_id);
			AnalyticsLogic::createSessionEngagement( 'post-read' );

			return Response::json(
					array('result' => 'success'),//great success!
					200
				);
		}
	}

	public function flagPost($post_id) {
		if( Auth::check() ) {
			$user = Auth::user();
			// If the flag exists, delete it, otherwise create it
			if ( $this->postFlagged->exists($user->id, $post_id) ) {
				$this->postFlagged->delete($user->id, $post_id);
				AnalyticsLogic::createSessionEngagement( 'unflag-post' );
			} else {
				$this->postFlagged->create($user->id, $post_id);
				AnalyticsLogic::createSessionEngagement( 'flag-post' );	
			}
			

			if($this->postFlagged->count($post_id) > 1) {
				$this->flaggedContent->create('post', $post_id);
			}

			return Response::json(
					array('result' => 'success'),//great success!
					200
				);
		}
	}

}