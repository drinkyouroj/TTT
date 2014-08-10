<?php
//Combines a lot of the older rest controller functions which did not require the entire REST Put and DEL stuff.
class RestController extends BaseController {

	public function __constructor(
						PostRepository $post,
						CommentRespository $comment,
						NotificationRepository $not

						) {
		$this->post = $post;
		$this->not = $not;
		$this->comment = $comment;

		//Below are for Repos that do not yet exist
		//$this->follow = $follow
		//$this->like = $like
		//$this->favorite = $favorite
		//$this->profilepost = $profilepost //This might change its name to feed or something.

	}

	//Like or Unlike a Post
	public function getLike() {
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
	public function postNoticed() {
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
	public function getFavorite() {
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
	public function getFollow() {
		//request sometimes comes in as a string
		$other_user_id = intval(Request::segment(3));
		$my_user_id = Auth::user()->id;
		$my_username = Auth::user()->username;
		
		if($other_user_id && !empty($other_user_id)) {
		
			$exists = Follow::where('user_id', $other_user_id)
							->where('follower_id', $my_user_id)
							->count();
			
			if($exists) {//Relationship already exists
				
				Follow::where('user_id', $other_user_id)
						->where('follower_id', $my_user_id)
						->delete();
				
				NotificationLogic::unfollow($other_user_id);
				
				return Response::json(
					array('result'=>'deleted'),
					200//response is OK!
				);
			} else {//Doesn't exists
				//Crete a new follow
				$follow = new Follow;
				$follow->user_id = $other_user_id;
				$follow->follower_id = $my_user_id;//Gotta be from you.
				$follow->save();
				
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
	public function getFollowers() {
		if($id) {
			$followers_id = Follow::where('user_id', '=', $id)
						->select('follower_id')
						->get();
			
			//Laravel, you suck.  Your stupid 'with' doesn't work.
			$ids = array();
			
			foreach($followers_id as $k => $follower) {
				$ids[$k] = $follower->follower_id;
			}
			
			//If there are no followers, then we don't grab the SQL.
			if(count($ids)) {
				$followers = User::whereIn('id', $ids)->select('id','username')->get()->toArray();
			} else {
				$followers = array();
			}
			
			return Response::json(
				array('followers'=> $followers),
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
	public function getFollowing() {
		if($id != 0) {
			$following_id = Follow::where('follower_id', '=', $id)
						->select('user_id')
						->get();
						
			//Laravel, you fucking suck.  Your stupid 'with' doesn't work.
			$ids = array();
			
			foreach($following_id as $k => $follower) {
				$ids[$k] = $follower->user_id;
			}
			
			//If there are no people you're following, then we don't grab the SQL.
			if(count($ids)) {
				$following = User::whereIn('id', $ids)->select('id','username')->get()->toArray();
			} else {
				$following = array();
			}
			
			return Response::json(
				array('following'=>$following),
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
	public function getRepost() {
		if(Request::segment(3) != 0) {
			$exists = Repost::where('post_id', '=', Request::segment(3))
							->where('user_id', '=', Auth::user()->id)
							->count();
			$owns = $this->post->owns(Request::segment(3), Auth::user()->id);
			
			if(!$exists && !$owns) {//Doesn't exists and you don't own it.
				//Crete a new repost
				$repost = new Repost;
				$repost->post_id = Request::segment(3);
				$repost->user_id = Auth::user()->id;//Gotta be from you.
				$repost->save();
									
				$post = $this->post->findById(Request::segment(3));
				
				NotificationLogic::repost($post);
				
				//This has to be outside 
				return Response::json(
					array('result'=>'success'),
					200//response is OK!
				);
			} elseif($exists) {//Relationship already exists
				
				Repost::where('post_id', '=', Request::segment(3))
					->where('user_id', '=', Auth::user()->id)
					->delete();
												
				NotificationLogic::unrepost(Request::segment(3));
				
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