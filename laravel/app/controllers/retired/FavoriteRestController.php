<?php
class FavoriteRestController extends \BaseController {

	public function __construct(PostRepository $post) {
		$this->post = $post;
	}

	//not the best usage (using get), but this works
	/**
		Marks or un-marks a post as a favorite.
	*/
	public function show()
	{
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

}