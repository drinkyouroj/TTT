<?php
//Combines a lot of the older rest controller functions which did not require the entire REST Put and DEL stuff.
class JSONController extends BaseController {

	public function __construct(
						PostRepository $post,
						CommentRepository $comment,
						NotificationRepository $not,
						FollowRepository $follow,
						RepostRepository $repost

						) {
		$this->post = $post;
		$this->not = $not;
		$this->comment = $comment;
		$this->follow = $follow;
		$this->repost = $repost;

		//Below are for Repos that do not yet exist
		//$this->like = $like
		//$this->favorite = $favorite
		//$this->profilepost = $profilepost //This might change its name to feed or something.

	}

	//Like or Unlike a Post
	public function getLikes() {
		if(Request::segment(3) != 0) {
			$exists = Like::where('post_id', '=', Request::segment(3))
							->where('user_id', '=', Auth::user()->id)
							->count();
							
			$owns = $this->post->owns(Request::segment(3), Auth::user()->id);
			
			if(!$exists && !$owns) {//Doesn't exists
				//Crete a new Like
				$like = new Like;
				$like->post_id = Request::segment(3);
				$like->user_id = Auth::user()->id;//Gotta be from you.
				$like->save();
				
				$this->post->incrementLike(Request::segment(3));
				
				if($like->id) {
					return Response::json(
						array('result'=>'success'),
						200//response is OK!
					);
				}
			} elseif($exists) {//Relationship already exists
			
				Like::where('post_id', '=', Request::segment(3))
					->where('user_id', '=', Auth::user()->id)
					->delete();
				
				$this->post->decrementLike(Request::segment(3));
				
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

	//Mark a post as a Favorite
	public function getFavorites() {
		if(Request::segment(3) != 0) {
			
			$user_id = Auth::user()->id;
			$post_id = Request::segment(3);

			$exists = Favorite::where('post_id', $post_id)
							->where('user_id', $user_id)
							->count();
							
			$owns = $this->post->owns($post_id, $user_id);
			$post = $this->post->findById($post_id);
			
			if(!$exists && !$owns) {//Relationship doesn't exist and the user doesn't own this.
				
				//Crete a new Favorite
				$favorite = new Favorite;
				$favorite->post_id = $post->id;
				$favorite->user_id = $user_id;//Gotta be from you.
				$favorite->save();
				
				//Add to activity
				$profilepost = new ProfilePost;
				$profilepost->post_id = $post->id;
				$profilepost->profile_id = $user_id;
				$profilepost->user_id = $post->user->id;
				$profilepost->post_type = 'favorite';
				$profilepost->save();
				
				NotificationLogic::favorite($post_id);
				
				return Response::json(
					array('result'=>'success'),
					200//response is OK!
				);
			
			} elseif($exists) {//Relationship already exists, should this be an unfavorite?
				
				//Delete from Favorites
				Favorite::where('post_id', $post_id)
						->where('user_id', $user_id)
						->delete();
				
				//Delete from My Posts
				ProfilePost::where('profile_id', $user_id)
						->where('post_id', $post_id)
						->where('user_id', $post->user->id)
						->delete();
				
				//Delete from Notifications
				NotificationLogic::unfavorite($post_id);
	
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
				
				return Response::json(
					array('result'=>'deleted'),
					200//response is OK!
				);
			} else {//Doesn't exists
				//Crete a new follow
				$this->follow->create($other_user_id, $my_user_id);
				
				NotificationLogic::follow($other_user_id);
				
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
					'followers'=> $this->follow->followers($id)
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
					'following'=> $this->follow->following($id)
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

	//Reposts a certain post.
	public function getReposts() {
		if(Request::segment(3) != 0) {

			$user_id = Auth::user()->id;
			$post_id = Request::segment(3);

			//Does this relationship already exist?
			$exists = $this->repost->exists($user_id, $post_id);

			//You can't repost your own stuff.
			$owns = $this->post->owns($post_id, $user_id);
			
			if(!$exists && !$owns) {//Doesn't exists and you don't own it.
				//Crete a new repost
				$this->repost->create($user_id, $post_id);
									
				$post = $this->post->findById($post_id);
				
				NotificationLogic::repost($post);
				
				//This has to be outside 
				return Response::json(
					array('result'=>'success'),
					200//response is OK!
				);
			} elseif($exists) {//Relationship already exists
				
				$this->repost->delete($user_id, $post_id);
												
				NotificationLogic::unrepost($post_id);
				
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


}