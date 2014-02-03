<?php
class FavoriteRestController extends \BaseController {

	public function index()
	{
		return Response::json(
			array('result'=>'success'),
			200//response is OK!
		);
	}

	public function store()
	{
		
	}
	
	//not the best usage (using get), but this works
	public function show()
	{
		$exists = Favorite::where('post_id', '=', Request::segment(3))
						->where('user_id', '=', Auth::user()->id)
						->count();
		$owns = Post::where('id', '=', Request::segment(3))
					->where('user_id', '=', Auth::user()->id)
					->count();
		if(!$exists && !$owns) {//Doesn't exists
			//Crete a new follow
			$favorite = new Favorite;
			$favorite->post_id = Request::segment(3);
			$favorite->user_id = Auth::user()->id;//Gotta be from you.
			$favorite->save();
			
			$post = Post::where('id','=', Request::segment(3))
						->first();
			
			//Add to activity
			$profilepost = new ProfilePost;
			$profilepost->post_id = Request::segment(3);
			$profilepost->profile_id = Auth::user()->id;
			$profilepost->user_id = $post->user->id;
			$profilepost->post_type = 'favorite';
			$profilepost->save();
			
			
			//Add to OP's notification
			$notification = new Notification;
			$notification->post_id = Request::segment(3);
			$notification->user_id = $post->user->id;
			$notification->action_id = Auth::user()->id;
			$notification->notification_type = 'favorite';
			$notification->save();
			
			return Response::json(
				array('result'=>'success'),
				200//response is OK!
			);
		
		} elseif($exists) {//Relationship already exists, should this be an unfavorite?
		
			$post = Post::where('id','=', Request::segment(3))
						->first();
		
			//Delete from Favorites
			Favorite::where('post_id', '=', Request::segment(3))
					->where('user_id', '=', Auth::user()->id)
					->delete();
			
			//Delete from Activity
			ProfilePost::where('profile_id', '=', Auth::user()->id)
					->where('post_id', '=', Request::segment(3))
					->where('user_id', '=', $post->user->id)
					->delete();
			
			//Delete from notifications
			Notification::where('post_id', '=', Request::segment(3))
					->where('action_id', '=', Auth::user()->id)
					->where('user_id', '=', $post->user->id)
					->where('notification_type', '=', 'favorite')
					->delete();
			

			return Response::json(
				array('result'=>'deleted'),
				200//response is OK!
			);
		}
		
		return Response::json(
			array('result'=>'fail'),
			200//response is OK!
		);
	}

	public function missingMethod($parameters)
	{
	    return Response::json(
			array('result'=>'fail'),
			200//response is OK!
		);
	}

}